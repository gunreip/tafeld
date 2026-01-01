--
-- PostgreSQL database dump
--

\restrict klfzbwhONOaehWUjxtm1uf3lWR4ScJOQWinv0OLhBNwYOPOLN3XlRD0yfNk1UuN

-- Dumped from database version 18.0 (Ubuntu 18.0-1.pgdg24.04+3)
-- Dumped by pg_dump version 18.0 (Ubuntu 18.0-1.pgdg24.04+3)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.countries (
    id bigint NOT NULL,
    iso_3166_2 character varying(2) NOT NULL,
    iso_3166_3 character varying(3) NOT NULL,
    name_en character varying(255) NOT NULL,
    name_de character varying(255),
    region character varying(255),
    subregion character varying(255),
    currency_code character varying(3),
    phone_code character varying(10),
    sort_key character varying(255),
    sort_key_de character varying(255),
    translit_en character varying(255),
    translit_de character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.countries OWNER TO gunreip;

--
-- Name: countries_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.countries_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.countries_id_seq OWNER TO gunreip;

--
-- Name: countries_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.countries_id_seq OWNED BY public.countries.id;


--
-- Name: countries id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.countries ALTER COLUMN id SET DEFAULT nextval('public.countries_id_seq'::regclass);


--
-- Name: countries countries_iso_3166_2_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_iso_3166_2_unique UNIQUE (iso_3166_2);


--
-- Name: countries countries_iso_3166_3_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_iso_3166_3_unique UNIQUE (iso_3166_3);


--
-- Name: countries countries_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.countries
    ADD CONSTRAINT countries_pkey PRIMARY KEY (id);


--
-- Name: countries_sort_key_de_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX countries_sort_key_de_index ON public.countries USING btree (sort_key_de);


--
-- Name: countries_sort_key_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX countries_sort_key_index ON public.countries USING btree (sort_key);


--
-- PostgreSQL database dump complete
--

\unrestrict klfzbwhONOaehWUjxtm1uf3lWR4ScJOQWinv0OLhBNwYOPOLN3XlRD0yfNk1UuN

