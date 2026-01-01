--
-- PostgreSQL database dump
--

\restrict wc5O1nka3yE8o4xawIm71WHnEZ8J6UhYcdfv0xbYClCi9vBOMgZrKORj95NpCrH

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
-- Name: holidays; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.holidays (
    id character(26) NOT NULL,
    country_id bigint NOT NULL,
    region_code character varying(5),
    date date NOT NULL,
    name_de character varying(255) NOT NULL,
    name_en character varying(255),
    translit_de character varying(255),
    translit_en character varying(255),
    sort_key_de character varying(255) NOT NULL,
    sort_key_en character varying(255),
    is_static boolean DEFAULT false NOT NULL,
    is_business_closed boolean,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.holidays OWNER TO gunreip;

--
-- Name: holidays holidays_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.holidays
    ADD CONSTRAINT holidays_pkey PRIMARY KEY (id);


--
-- Name: holidays unique_holiday_per_region; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.holidays
    ADD CONSTRAINT unique_holiday_per_region UNIQUE (country_id, region_code, date);


--
-- Name: holidays holidays_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.holidays
    ADD CONSTRAINT holidays_country_id_foreign FOREIGN KEY (country_id) REFERENCES public.countries(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict wc5O1nka3yE8o4xawIm71WHnEZ8J6UhYcdfv0xbYClCi9vBOMgZrKORj95NpCrH

