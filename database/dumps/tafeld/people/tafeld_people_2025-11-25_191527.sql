--
-- PostgreSQL database dump
--

\restrict iKjlIH4aEacC3GFKcGRNgcpgyGSzy138snzmb8P7DkrpDLFC49m4OgDq2d8oLZM

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
-- Name: people; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.people (
    id character(26) NOT NULL,
    first_name character varying(255) NOT NULL,
    last_name character varying(255) NOT NULL,
    first_name_sort_key character varying(255),
    first_name_translit character varying(255),
    last_name_sort_key character varying(255),
    last_name_translit character varying(255),
    street character varying(255),
    house_number character varying(255),
    country_id bigint,
    zipcode character varying(255),
    city character varying(255),
    nationality_id bigint,
    birthdate date,
    employment_start date,
    employment_end date,
    mobile_country_id bigint,
    mobile_area character varying(255),
    mobile_number character varying(255),
    phone_country_id bigint,
    phone_area character varying(255),
    phone_number character varying(255),
    email_local character varying(255),
    email_domain character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.people OWNER TO gunreip;

--
-- Name: people people_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_pkey PRIMARY KEY (id);


--
-- Name: people people_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_country_id_foreign FOREIGN KEY (country_id) REFERENCES public.countries(id) ON DELETE RESTRICT;


--
-- Name: people people_mobile_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_mobile_country_id_foreign FOREIGN KEY (mobile_country_id) REFERENCES public.countries(id) ON DELETE RESTRICT;


--
-- Name: people people_nationality_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_nationality_id_foreign FOREIGN KEY (nationality_id) REFERENCES public.countries(id) ON DELETE RESTRICT;


--
-- Name: people people_phone_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_phone_country_id_foreign FOREIGN KEY (phone_country_id) REFERENCES public.countries(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict iKjlIH4aEacC3GFKcGRNgcpgyGSzy138snzmb8P7DkrpDLFC49m4OgDq2d8oLZM

