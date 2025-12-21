--
-- PostgreSQL database dump
--

\restrict e9Zv9BKSRW7YNumG94kdE0aPTQ4sK2Mz4Gpy2du8ljuQer8wGExzaSdF6tjQVmP

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
-- Name: debug_logs; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.debug_logs (
    id character(26) NOT NULL,
    scope character varying(255) NOT NULL,
    channel character varying(255),
    level character varying(255) NOT NULL,
    message text NOT NULL,
    context jsonb,
    user_id character(26),
    created_at timestamp(0) without time zone NOT NULL,
    run_id character(26) NOT NULL,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.debug_logs OWNER TO gunreip;

--
-- Name: debug_logs debug_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_logs
    ADD CONSTRAINT debug_logs_pkey PRIMARY KEY (id);


--
-- Name: debug_logs_created_at_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX debug_logs_created_at_index ON public.debug_logs USING btree (created_at);


--
-- Name: debug_logs_run_id_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX debug_logs_run_id_index ON public.debug_logs USING btree (run_id);


--
-- Name: debug_logs_scope_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX debug_logs_scope_index ON public.debug_logs USING btree (scope);


--
-- Name: debug_logs debug_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_logs
    ADD CONSTRAINT debug_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(ulid) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict e9Zv9BKSRW7YNumG94kdE0aPTQ4sK2Mz4Gpy2du8ljuQer8wGExzaSdF6tjQVmP

