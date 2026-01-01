--
-- PostgreSQL database dump
--

\restrict h07z3ycfd7fs5URTINRVjfIQM58axgzIxCchtJRpbnD9AM4MLDeX4e4wjcipF3H

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
-- Name: debug_scopes; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.debug_scopes (
    id character(26) NOT NULL,
    scope_key character varying(255) NOT NULL,
    enabled boolean DEFAULT true NOT NULL,
    file_path character varying(255),
    options jsonb,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    first_seen_at timestamp(0) without time zone,
    last_seen_at timestamp(0) without time zone,
    origin character varying(50),
    route character varying(255),
    component character varying(255),
    field character varying(255),
    description text
);


ALTER TABLE public.debug_scopes OWNER TO gunreip;

--
-- Name: debug_scopes debug_scopes_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_scopes
    ADD CONSTRAINT debug_scopes_pkey PRIMARY KEY (id);


--
-- Name: debug_scopes debug_scopes_scope_key_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_scopes
    ADD CONSTRAINT debug_scopes_scope_key_unique UNIQUE (scope_key);


--
-- PostgreSQL database dump complete
--

\unrestrict h07z3ycfd7fs5URTINRVjfIQM58axgzIxCchtJRpbnD9AM4MLDeX4e4wjcipF3H

