import 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js';
import 'select2/dist/css/select2.min.css';
import '../vendor/select2/dist/css/select2-bootstrap-5-theme.min.css';
import 'https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js';
import 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js';

//regular select2
$('.s2-select').select2({
    theme: 'bootstrap-5',
    language: "fr",
})



//city finder select2
$('.s2-select.city-finder').select2({
    theme: 'bootstrap-5',
    language: "fr",
    // tags: true,
    placeholder: "Commencez à entrez une adresse...",
    ajax: {
        url: 'https://api-adresse.data.gouv.fr/search/?q=8+bd+du+port&limit=15',
        delay: 1000, //wait 1sec before sending a request
        data: function (params) {
            //prevent empty string
            if (typeof(params.term) == 'undefined') {
                throw false;
            }
            //allow minimum input info
            else if(params.term.trim().length < 2){
                throw false;
            }
            else {
                var query = {
                    q: params.term.trim(),
                //   page: params.page || 1
                }
                return query;
            }
        },
        processResults: function (data) {
            // Transforms results
            $('input.geocities-result').data('results', JSON.stringify(data.features))
            // console.log(data.features)
            //group by type of result
            let groupedResults = _.groupBy(data.features, function(o){
                return o.properties.type
            })
            //type of result names
            const metaname = {
                'housenumber': 'Adresse précise',
                'street': 'Voie',
                'locality': 'Lieu-dit',
                'municipality': 'Ville',
            }
            //return grouped results with select2 format
            var groups = [];
            _.forEach(groupedResults, function(group, label){
                groups.push(
                    {
                        'text': metaname[label],
                        'children' : group.map(function(o){
                            let label = '';
                            if(o.properties.type == 'municipality') {
                                label = o.properties.name + ' - ' + o.properties.postcode + ' (' + o.properties.context + ')'
                            } 
                            else {
                                label = o.properties.name + ' - ' + o.properties.postcode + ' ' + o.properties.city + ' (' + o.properties.context + ')'
                            }
                            return {
                                "id": o.properties.id,
                                "text": label
                            }
                        })
                    }
                )
            })
            return {results : groups};
        }
    }
})

$('.s2-select.city-finder').on('select2:select', function (e) {
    const selectedCode = $('.s2-select.city-finder').val();
    const selectedLabel = $('.s2-select.city-finder option:selected').html();
    var data = JSON.parse($('input.geocities-result').data('results'));
    var selectedGeoCity = _.find(data, function(o) { return o.properties.id == selectedCode });
    console.log("selected code : ", selectedCode, data, selectedGeoCity);
    //empty fields
    $('input[name="circle_form[address]"]').val('')
    $('input[name="circle_form[address_label]"]').val('')
    $('input[name="circle_form[postcode]"]').val('')
    $('input[name="circle_form[city]"]').val('')
    $('input[name="circle_form[insee_code]"]').val('')
    $('input[name="circle_form[lng]"]').val('')
    $('input[name="circle_form[lat]"]').val('')
    
    //populate hidden fields
    $('input[name="circle_form[address]"]').val(selectedGeoCity.properties.name)
    $('input[name="circle_form[address_label]"]').val(selectedLabel)
    $('input[name="circle_form[postcode]"]').val(selectedGeoCity.properties.postcode)
    $('input[name="circle_form[city]"]').val(selectedGeoCity.properties.city)
    $('input[name="circle_form[insee_code]"]').val(selectedGeoCity.properties.citycode)
    $('input[name="circle_form[lng]"]').val(selectedGeoCity.geometry.coordinates[0])
    $('input[name="circle_form[lat]"]').val(selectedGeoCity.geometry.coordinates[1])
});
