--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: donoussa; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA donoussa;


ALTER SCHEMA donoussa OWNER TO postgres;

SET search_path = donoussa, pg_catalog;

--
-- Name: page_dependencies_id_seq; Type: SEQUENCE; Schema: donoussa; Owner: postgres
--

CREATE SEQUENCE page_dependencies_id_seq
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;


ALTER TABLE page_dependencies_id_seq OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: page_dependencies; Type: TABLE; Schema: donoussa; Owner: postgres; Tablespace:
--

CREATE TABLE page_dependencies (
    id integer DEFAULT nextval('page_dependencies_id_seq'::regclass) NOT NULL,
    page_id character varying(200) NOT NULL,
    modernizr_js character varying(200),
    jquery_js character varying(200),
    jquery_ui_js character varying(200),
    jquery_ui_css character varying(200),
    bootstrap_css character varying(200),
    bootstrap_js character varying(200),
    font_awesome_css character varying(200),
    touch_punch_js character varying(200),
    bowser_js character varying(200),
    momentjs_js character varying(200),
    momentjs_i18n_js character varying(200),
    jquery_ui_autocomplete_html_js character varying(200),
    datepicker_i18n_js character varying(200),
    timepicker_css character varying(200),
    timepicker_js character varying(200),
    timepicker_i18n_js character varying(200),
    google_maps_api_js character varying(200),
    php_bs_grid_css character varying(200),
    php_bs_grid_js character varying(200),
    html5shiv_js character varying(200),
    respond_js character varying(200),
    common_css character varying(200),
    page_css character varying(200),
    common_js character varying(200),
    page_js character varying(200),
    webfont_medical_icons character varying(200),
    imageloaded_js character varying(200),
    chartjs_js character varying(200)
);


ALTER TABLE page_dependencies OWNER TO postgres;

--
-- Name: page_properties_id_seq; Type: SEQUENCE; Schema: donoussa; Owner: postgres
--

CREATE SEQUENCE page_properties_id_seq
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;


ALTER TABLE page_properties_id_seq OWNER TO postgres;

--
-- Name: page_properties; Type: TABLE; Schema: donoussa; Owner: postgres; Tablespace:
--

CREATE TABLE page_properties (
    id integer DEFAULT nextval('page_properties_id_seq'::regclass) NOT NULL,
    page_id character varying(200) NOT NULL,
    real_url character varying(200) NOT NULL,
    unique_url smallint NOT NULL,
    title character varying(200),
    description character varying(160),
    tag character varying(200),
    package character varying(200),
    auth_required smallint NOT NULL,
    roles character varying(50) NOT NULL,
    model_filename character varying(200),
    view_filename character varying(200),
    header character varying(200) NOT NULL,
    footer character varying(200) NOT NULL,
    modal_dialog smallint,
    modal_confirm smallint,
    is_alias_of integer
);


ALTER TABLE page_properties OWNER TO postgres;

--
-- Name: page_url_id_seq; Type: SEQUENCE; Schema: donoussa; Owner: postgres
--

CREATE SEQUENCE page_url_id_seq
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;


ALTER TABLE page_url_id_seq OWNER TO postgres;

--
-- Name: page_url; Type: TABLE; Schema: donoussa; Owner: postgres; Tablespace:
--

CREATE TABLE page_url (
    id integer DEFAULT nextval('page_url_id_seq'::regclass) NOT NULL,
    page_id character varying(200) NOT NULL,
    url character varying(200) NOT NULL,
    title character varying(200),
    title_param character varying(200),
    description character varying(160),
    request_type smallint NOT NULL,
    security_check character varying(8)
);


ALTER TABLE page_url OWNER TO postgres;

--
-- Name: page_dependencies_page_id_key; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_dependencies
    ADD CONSTRAINT page_dependencies_page_id_key UNIQUE (page_id);


--
-- Name: page_dependencies_pkey; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_dependencies
    ADD CONSTRAINT page_dependencies_pkey PRIMARY KEY (id);


--
-- Name: page_properties_page_id_key; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_properties
    ADD CONSTRAINT page_properties_page_id_key UNIQUE (page_id);


--
-- Name: page_properties_pkey; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_properties
    ADD CONSTRAINT page_properties_pkey PRIMARY KEY (id);


--
-- Name: page_url_pkey; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_url
    ADD CONSTRAINT page_url_pkey PRIMARY KEY (id);


--
-- Name: page_url_url_key; Type: CONSTRAINT; Schema: donoussa; Owner: postgres; Tablespace:
--

ALTER TABLE ONLY page_url
    ADD CONSTRAINT page_url_url_key UNIQUE (url);


--
-- Name: page_url_page_id_idx; Type: INDEX; Schema: donoussa; Owner: postgres; Tablespace:
--

CREATE INDEX page_url_page_id_idx ON page_url USING btree (page_id);


--
-- PostgreSQL database dump complete
--

