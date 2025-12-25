--
-- PostgreSQL database dump
--

\restrict 3k7J0R5dgCh3AzwBDf7oyK0khMTJnwoNSWCXjjf2O5CJ7vuxuIFGvdYKQz85nk8

-- Dumped from database version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)
-- Dumped by pg_dump version 18.1 (Ubuntu 18.1-1.pgdg24.04+2)

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
-- Name: debug_settings; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.debug_settings (
    id character(26) NOT NULL,
    enabled boolean DEFAULT true NOT NULL,
    reset_on_run boolean DEFAULT false NOT NULL,
    channels jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    scope_key character varying(255) NOT NULL
);


ALTER TABLE public.debug_settings OWNER TO gunreip;

--
-- Name: debug_settings debug_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_settings
    ADD CONSTRAINT debug_settings_pkey PRIMARY KEY (id);


--
-- Name: debug_settings debug_settings_scope_key_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_settings
    ADD CONSTRAINT debug_settings_scope_key_unique UNIQUE (scope_key);


--
-- PostgreSQL database dump complete
--

\unrestrict 3k7J0R5dgCh3AzwBDf7oyK0khMTJnwoNSWCXjjf2O5CJ7vuxuIFGvdYKQz85nk8

