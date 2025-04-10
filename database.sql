--
-- PostgreSQL database dump
--

-- Dumped from database version 16.8 (Postgres.app)
-- Dumped by pg_dump version 16.8 (Postgres.app)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: notify_messenger_messages(); Type: FUNCTION; Schema: public; Owner: corentoulf
--

CREATE FUNCTION public.notify_messenger_messages() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
            BEGIN
                PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$;


ALTER FUNCTION public.notify_messenger_messages() OWNER TO corentoulf;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: circle; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.circle (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    address character varying(255) DEFAULT NULL::character varying,
    postcode character varying(255) DEFAULT NULL::character varying,
    city character varying(255) DEFAULT NULL::character varying,
    country character varying(255) DEFAULT NULL::character varying,
    created_by_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    short_id character varying(255) DEFAULT NULL::character varying NOT NULL,
    circle_type character varying(255) DEFAULT NULL::character varying NOT NULL,
    insee_code character varying(10) DEFAULT NULL::character varying,
    lat character varying(255) DEFAULT NULL::character varying,
    lng character varying(255) DEFAULT NULL::character varying,
    address_label character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.circle OWNER TO corentoulf;

--
-- Name: circle_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.circle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.circle_id_seq OWNER TO corentoulf;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO corentoulf;

--
-- Name: item; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.item (
    id integer NOT NULL,
    owner_id integer NOT NULL,
    description character varying(255) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    item_type_id integer,
    property_1 character varying(255) DEFAULT NULL::character varying,
    property_2 character varying(255) DEFAULT NULL::character varying,
    property_3 character varying(255) DEFAULT NULL::character varying,
    property_4 character varying(255) DEFAULT NULL::character varying,
    property_5 character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.item OWNER TO corentoulf;

--
-- Name: COLUMN item.created_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.item.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: item_category; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.item_category (
    id integer NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.item_category OWNER TO corentoulf;

--
-- Name: item_category_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.item_category_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.item_category_id_seq OWNER TO corentoulf;

--
-- Name: item_circle; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.item_circle (
    id integer NOT NULL,
    item_id integer NOT NULL,
    circle_id integer NOT NULL
);


ALTER TABLE public.item_circle OWNER TO corentoulf;

--
-- Name: item_circle_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.item_circle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.item_circle_id_seq OWNER TO corentoulf;

--
-- Name: item_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.item_id_seq OWNER TO corentoulf;

--
-- Name: item_type; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.item_type (
    id integer NOT NULL,
    property_1_label character varying(255) DEFAULT NULL::character varying,
    property_2_label character varying(255) DEFAULT NULL::character varying,
    property_3_label character varying(255) DEFAULT NULL::character varying,
    property_4_label character varying(255) DEFAULT NULL::character varying,
    property_5_label character varying(255) DEFAULT NULL::character varying,
    code character varying(255) NOT NULL,
    category_id integer
);


ALTER TABLE public.item_type OWNER TO corentoulf;

--
-- Name: item_type_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.item_type_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.item_type_id_seq OWNER TO corentoulf;

--
-- Name: messenger_messages; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.messenger_messages (
    id bigint NOT NULL,
    body text NOT NULL,
    headers text NOT NULL,
    queue_name character varying(190) NOT NULL,
    created_at timestamp(0) without time zone NOT NULL,
    available_at timestamp(0) without time zone NOT NULL,
    delivered_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone
);


ALTER TABLE public.messenger_messages OWNER TO corentoulf;

--
-- Name: COLUMN messenger_messages.created_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.messenger_messages.created_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.available_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.messenger_messages.available_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN messenger_messages.delivered_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)';


--
-- Name: messenger_messages_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.messenger_messages_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.messenger_messages_id_seq OWNER TO corentoulf;

--
-- Name: messenger_messages_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: corentoulf
--

ALTER SEQUENCE public.messenger_messages_id_seq OWNED BY public.messenger_messages.id;


--
-- Name: reset_password_request; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.reset_password_request (
    id integer NOT NULL,
    user_id integer NOT NULL,
    selector character varying(20) NOT NULL,
    hashed_token character varying(100) NOT NULL,
    requested_at timestamp(0) without time zone NOT NULL,
    expires_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.reset_password_request OWNER TO corentoulf;

--
-- Name: COLUMN reset_password_request.requested_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.reset_password_request.requested_at IS '(DC2Type:datetime_immutable)';


--
-- Name: COLUMN reset_password_request.expires_at; Type: COMMENT; Schema: public; Owner: corentoulf
--

COMMENT ON COLUMN public.reset_password_request.expires_at IS '(DC2Type:datetime_immutable)';


--
-- Name: reset_password_request_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.reset_password_request_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reset_password_request_id_seq OWNER TO corentoulf;

--
-- Name: user; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    is_verified boolean NOT NULL,
    phone character varying(20) DEFAULT NULL::character varying,
    created_at timestamp(0) without time zone NOT NULL,
    first_name character varying(255) DEFAULT NULL::character varying,
    last_name character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public."user" OWNER TO corentoulf;

--
-- Name: user_circle; Type: TABLE; Schema: public; Owner: corentoulf
--

CREATE TABLE public.user_circle (
    id integer NOT NULL,
    user_id_id integer NOT NULL,
    circle_id integer NOT NULL,
    created_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.user_circle OWNER TO corentoulf;

--
-- Name: user_circle_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.user_circle_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_circle_id_seq OWNER TO corentoulf;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: corentoulf
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_id_seq OWNER TO corentoulf;

--
-- Name: messenger_messages id; Type: DEFAULT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.messenger_messages ALTER COLUMN id SET DEFAULT nextval('public.messenger_messages_id_seq'::regclass);


--
-- Name: circle circle_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.circle
    ADD CONSTRAINT circle_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: item_category item_category_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_category
    ADD CONSTRAINT item_category_pkey PRIMARY KEY (id);


--
-- Name: item_circle item_circle_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_circle
    ADD CONSTRAINT item_circle_pkey PRIMARY KEY (id);


--
-- Name: item item_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item
    ADD CONSTRAINT item_pkey PRIMARY KEY (id);


--
-- Name: item_type item_type_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_type
    ADD CONSTRAINT item_type_pkey PRIMARY KEY (id);


--
-- Name: messenger_messages messenger_messages_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.messenger_messages
    ADD CONSTRAINT messenger_messages_pkey PRIMARY KEY (id);


--
-- Name: reset_password_request reset_password_request_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.reset_password_request
    ADD CONSTRAINT reset_password_request_pkey PRIMARY KEY (id);


--
-- Name: user_circle user_circle_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.user_circle
    ADD CONSTRAINT user_circle_pkey PRIMARY KEY (id);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: idx_1f1b251e7e3c61f9; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_1f1b251e7e3c61f9 ON public.item USING btree (owner_id);


--
-- Name: idx_1f1b251ece11aac7; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_1f1b251ece11aac7 ON public.item USING btree (item_type_id);


--
-- Name: idx_44ee13d212469de2; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_44ee13d212469de2 ON public.item_type USING btree (category_id);


--
-- Name: idx_524e10b6126f525e; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_524e10b6126f525e ON public.item_circle USING btree (item_id);


--
-- Name: idx_524e10b670ee2ff6; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_524e10b670ee2ff6 ON public.item_circle USING btree (circle_id);


--
-- Name: idx_75ea56e016ba31db; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_75ea56e016ba31db ON public.messenger_messages USING btree (delivered_at);


--
-- Name: idx_75ea56e0e3bd61ce; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_75ea56e0e3bd61ce ON public.messenger_messages USING btree (available_at);


--
-- Name: idx_75ea56e0fb7336f0; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_75ea56e0fb7336f0 ON public.messenger_messages USING btree (queue_name);


--
-- Name: idx_7ce748aa76ed395; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_7ce748aa76ed395 ON public.reset_password_request USING btree (user_id);


--
-- Name: idx_b1e57e4470ee2ff6; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_b1e57e4470ee2ff6 ON public.user_circle USING btree (circle_id);


--
-- Name: idx_b1e57e449d86650f; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_b1e57e449d86650f ON public.user_circle USING btree (user_id_id);


--
-- Name: idx_d4b76579b03a8386; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE INDEX idx_d4b76579b03a8386 ON public.circle USING btree (created_by_id);


--
-- Name: uniq_identifier_email; Type: INDEX; Schema: public; Owner: corentoulf
--

CREATE UNIQUE INDEX uniq_identifier_email ON public."user" USING btree (email);


--
-- Name: messenger_messages notify_trigger; Type: TRIGGER; Schema: public; Owner: corentoulf
--

CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON public.messenger_messages FOR EACH ROW EXECUTE FUNCTION public.notify_messenger_messages();


--
-- Name: item fk_1f1b251e7e3c61f9; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item
    ADD CONSTRAINT fk_1f1b251e7e3c61f9 FOREIGN KEY (owner_id) REFERENCES public."user"(id);


--
-- Name: item fk_1f1b251ece11aac7; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item
    ADD CONSTRAINT fk_1f1b251ece11aac7 FOREIGN KEY (item_type_id) REFERENCES public.item_type(id);


--
-- Name: item_type fk_44ee13d212469de2; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_type
    ADD CONSTRAINT fk_44ee13d212469de2 FOREIGN KEY (category_id) REFERENCES public.item_category(id);


--
-- Name: item_circle fk_524e10b6126f525e; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_circle
    ADD CONSTRAINT fk_524e10b6126f525e FOREIGN KEY (item_id) REFERENCES public.item(id);


--
-- Name: item_circle fk_524e10b670ee2ff6; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.item_circle
    ADD CONSTRAINT fk_524e10b670ee2ff6 FOREIGN KEY (circle_id) REFERENCES public.circle(id);


--
-- Name: reset_password_request fk_7ce748aa76ed395; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.reset_password_request
    ADD CONSTRAINT fk_7ce748aa76ed395 FOREIGN KEY (user_id) REFERENCES public."user"(id);


--
-- Name: user_circle fk_b1e57e4470ee2ff6; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.user_circle
    ADD CONSTRAINT fk_b1e57e4470ee2ff6 FOREIGN KEY (circle_id) REFERENCES public.circle(id);


--
-- Name: user_circle fk_b1e57e449d86650f; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.user_circle
    ADD CONSTRAINT fk_b1e57e449d86650f FOREIGN KEY (user_id_id) REFERENCES public."user"(id);


--
-- Name: circle fk_d4b76579b03a8386; Type: FK CONSTRAINT; Schema: public; Owner: corentoulf
--

ALTER TABLE ONLY public.circle
    ADD CONSTRAINT fk_d4b76579b03a8386 FOREIGN KEY (created_by_id) REFERENCES public."user"(id);


--
-- PostgreSQL database dump complete
--

