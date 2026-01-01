--
-- PostgreSQL database dump
--

\restrict A57J9wQSidGfL1rx2PTb2iIeNmbaM2a8ngfddj9rjrZnzVQGvrEGH0RVkg7ts7C

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
    email character varying(255),
    phone character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    country_id bigint
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
    ADD CONSTRAINT people_country_id_foreign FOREIGN KEY (country_id) REFERENCES public.countries(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict A57J9wQSidGfL1rx2PTb2iIeNmbaM2a8ngfddj9rjrZnzVQGvrEGH0RVkg7ts7C

