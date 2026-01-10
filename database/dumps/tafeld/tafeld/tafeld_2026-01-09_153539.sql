--
-- PostgreSQL database dump
--

\restrict WRYVdjA44fKYIsS1AZgHhIBqjlF01VwUPtWpLqf51zzylorhyyJsdySWYnOMfkD

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
-- Name: activity_log; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.activity_log (
    id bigint NOT NULL,
    log_name character varying(255),
    description text NOT NULL,
    subject_id character varying(26),
    subject_type character varying(255),
    causer_type character varying(255),
    causer_id bigint,
    properties json,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    event character varying(255),
    batch_uuid uuid
);


ALTER TABLE public.activity_log OWNER TO gunreip;

--
-- Name: activity_log_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.activity_log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.activity_log_id_seq OWNER TO gunreip;

--
-- Name: activity_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.activity_log_id_seq OWNED BY public.activity_log.id;


--
-- Name: app_settings; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.app_settings (
    ulid character(26) NOT NULL,
    key character varying(255) NOT NULL,
    value json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.app_settings OWNER TO gunreip;

--
-- Name: app_user_settings; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.app_user_settings (
    ulid character(26) NOT NULL,
    user_ulid character(26) NOT NULL,
    key character varying(255) NOT NULL,
    value json NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.app_user_settings OWNER TO gunreip;

--
-- Name: cache; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache OWNER TO gunreip;

--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration integer NOT NULL
);


ALTER TABLE public.cache_locks OWNER TO gunreip;

--
-- Name: countries; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.countries (
    id character(26) NOT NULL,
    iso_3166_2 character varying(2) NOT NULL,
    iso_3166_3 character varying(3) NOT NULL,
    name_en character varying(255) NOT NULL,
    name_de character varying(255),
    region character varying(255),
    subregion character varying(255),
    currency_code character varying(3),
    phone_code character varying(10),
    sort_key_en character varying(255),
    sort_key_de character varying(255),
    translit_en character varying(255),
    translit_de character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    work_area character varying(255) DEFAULT 'THIRD_COUNTRY'::character varying NOT NULL,
    CONSTRAINT countries_work_area_check CHECK (((work_area)::text = ANY ((ARRAY['EU_EEA_SWISS'::character varying, 'PRIVILEGED'::character varying, 'THIRD_COUNTRY'::character varying])::text[])))
);


ALTER TABLE public.countries OWNER TO gunreip;

--
-- Name: country_regions; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.country_regions (
    id character(26) NOT NULL,
    country_id character(26) NOT NULL,
    iso_3166_2 character varying(16),
    iso_3166_3 character varying(32),
    name_de character varying(128) NOT NULL,
    name_en character varying(128),
    fullname_de character varying(256),
    fullname_en character varying(256),
    translit_de character varying(128),
    translit_en character varying(128),
    sort_key_de character varying(128),
    sort_key_en character varying(128),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.country_regions OWNER TO gunreip;

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
    description text,
    runtime_killable boolean DEFAULT true NOT NULL
);


ALTER TABLE public.debug_scopes OWNER TO gunreip;

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
-- Name: events; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.events (
    id character(26) NOT NULL,
    country_region_id character(26) NOT NULL,
    event_type character varying(32) NOT NULL,
    name_de character varying(128) NOT NULL,
    name_en character varying(128),
    translit_de character varying(128),
    translit_en character varying(128),
    sort_key_de character varying(128),
    sort_key_en character varying(128),
    start_date date NOT NULL,
    end_date date NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.events OWNER TO gunreip;

--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO gunreip;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.failed_jobs_id_seq OWNER TO gunreip;

--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: holidays; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.holidays (
    id character(26) NOT NULL,
    country_id character(26) NOT NULL,
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
    updated_at timestamp(0) without time zone,
    display_date boolean DEFAULT true NOT NULL,
    confession character varying(32)
);


ALTER TABLE public.holidays OWNER TO gunreip;

--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


ALTER TABLE public.job_batches OWNER TO gunreip;

--
-- Name: jobs; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


ALTER TABLE public.jobs OWNER TO gunreip;

--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.jobs_id_seq OWNER TO gunreip;

--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO gunreip;

--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.migrations_id_seq OWNER TO gunreip;

--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: model_has_permissions; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.model_has_permissions (
    permission_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_permissions OWNER TO gunreip;

--
-- Name: model_has_roles; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.model_has_roles (
    role_id bigint NOT NULL,
    model_type character varying(255) NOT NULL,
    model_id bigint NOT NULL
);


ALTER TABLE public.model_has_roles OWNER TO gunreip;

--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_reset_tokens OWNER TO gunreip;

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
    country_id character(26),
    zipcode character varying(255),
    city character varying(255),
    nationality_id character(26),
    birthdate date,
    employment_start date,
    employment_end date,
    mobile_country_id character(26),
    mobile_area character varying(255),
    mobile_number character varying(255),
    phone_country_id character(26),
    phone_area character varying(255),
    phone_number character varying(255),
    email_local character varying(255),
    email_domain character varying(255),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.people OWNER TO gunreip;

--
-- Name: permissions; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.permissions OWNER TO gunreip;

--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.permissions_id_seq OWNER TO gunreip;

--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.personal_access_tokens OWNER TO gunreip;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.personal_access_tokens_id_seq OWNER TO gunreip;

--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: role_has_permissions; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.role_has_permissions (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


ALTER TABLE public.role_has_permissions OWNER TO gunreip;

--
-- Name: roles; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    guard_name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.roles OWNER TO gunreip;

--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_id_seq OWNER TO gunreip;

--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


ALTER TABLE public.sessions OWNER TO gunreip;

--
-- Name: telescope_entries; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.telescope_entries (
    sequence bigint NOT NULL,
    uuid uuid NOT NULL,
    batch_id uuid NOT NULL,
    family_hash character varying(255),
    should_display_on_index boolean DEFAULT true NOT NULL,
    type character varying(20) NOT NULL,
    content text NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.telescope_entries OWNER TO gunreip;

--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.telescope_entries_sequence_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.telescope_entries_sequence_seq OWNER TO gunreip;

--
-- Name: telescope_entries_sequence_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.telescope_entries_sequence_seq OWNED BY public.telescope_entries.sequence;


--
-- Name: telescope_entries_tags; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.telescope_entries_tags (
    entry_uuid uuid NOT NULL,
    tag character varying(255) NOT NULL
);


ALTER TABLE public.telescope_entries_tags OWNER TO gunreip;

--
-- Name: telescope_monitoring; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.telescope_monitoring (
    tag character varying(255) NOT NULL
);


ALTER TABLE public.telescope_monitoring OWNER TO gunreip;

--
-- Name: ui_preferences; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.ui_preferences (
    id character(26) NOT NULL,
    user_id character(26),
    scope character varying(64) NOT NULL,
    key character varying(128) NOT NULL,
    value character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.ui_preferences OWNER TO gunreip;

--
-- Name: users; Type: TABLE; Schema: public; Owner: gunreip
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    two_factor_secret text,
    two_factor_recovery_codes text,
    two_factor_confirmed_at timestamp(0) without time zone,
    ulid character(26)
);


ALTER TABLE public.users OWNER TO gunreip;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: gunreip
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO gunreip;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: gunreip
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: activity_log id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.activity_log ALTER COLUMN id SET DEFAULT nextval('public.activity_log_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: telescope_entries sequence; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_entries ALTER COLUMN sequence SET DEFAULT nextval('public.telescope_entries_sequence_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: activity_log activity_log_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.activity_log
    ADD CONSTRAINT activity_log_pkey PRIMARY KEY (id);


--
-- Name: app_settings app_settings_key_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.app_settings
    ADD CONSTRAINT app_settings_key_unique UNIQUE (key);


--
-- Name: app_settings app_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.app_settings
    ADD CONSTRAINT app_settings_pkey PRIMARY KEY (ulid);


--
-- Name: app_user_settings app_user_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.app_user_settings
    ADD CONSTRAINT app_user_settings_pkey PRIMARY KEY (ulid);


--
-- Name: app_user_settings app_user_settings_user_ulid_key_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.app_user_settings
    ADD CONSTRAINT app_user_settings_user_ulid_key_unique UNIQUE (user_ulid, key);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


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
-- Name: country_regions country_regions_country_id_iso_3166_2_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.country_regions
    ADD CONSTRAINT country_regions_country_id_iso_3166_2_unique UNIQUE (country_id, iso_3166_2);


--
-- Name: country_regions country_regions_country_id_iso_3166_3_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.country_regions
    ADD CONSTRAINT country_regions_country_id_iso_3166_3_unique UNIQUE (country_id, iso_3166_3);


--
-- Name: country_regions country_regions_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.country_regions
    ADD CONSTRAINT country_regions_pkey PRIMARY KEY (id);


--
-- Name: debug_logs debug_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_logs
    ADD CONSTRAINT debug_logs_pkey PRIMARY KEY (id);


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
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: holidays holidays_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.holidays
    ADD CONSTRAINT holidays_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: model_has_permissions model_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_pkey PRIMARY KEY (permission_id, model_id, model_type);


--
-- Name: model_has_roles model_has_roles_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_pkey PRIMARY KEY (role_id, model_id, model_type);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: people people_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.people
    ADD CONSTRAINT people_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: role_has_permissions role_has_permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: roles roles_name_guard_name_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_name_guard_name_unique UNIQUE (name, guard_name);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: telescope_entries telescope_entries_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_pkey PRIMARY KEY (sequence);


--
-- Name: telescope_entries_tags telescope_entries_tags_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_entries_tags
    ADD CONSTRAINT telescope_entries_tags_pkey PRIMARY KEY (entry_uuid, tag);


--
-- Name: telescope_entries telescope_entries_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_entries
    ADD CONSTRAINT telescope_entries_uuid_unique UNIQUE (uuid);


--
-- Name: telescope_monitoring telescope_monitoring_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_monitoring
    ADD CONSTRAINT telescope_monitoring_pkey PRIMARY KEY (tag);


--
-- Name: ui_preferences ui_preferences_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.ui_preferences
    ADD CONSTRAINT ui_preferences_pkey PRIMARY KEY (id);


--
-- Name: ui_preferences ui_preferences_user_scope_key_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.ui_preferences
    ADD CONSTRAINT ui_preferences_user_scope_key_unique UNIQUE (user_id, scope, key);


--
-- Name: holidays unique_holiday_per_region; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.holidays
    ADD CONSTRAINT unique_holiday_per_region UNIQUE (country_id, region_code, date);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_ulid_unique; Type: CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_ulid_unique UNIQUE (ulid);


--
-- Name: activity_log_log_name_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX activity_log_log_name_index ON public.activity_log USING btree (log_name);


--
-- Name: activity_log_subject_id_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX activity_log_subject_id_index ON public.activity_log USING btree (subject_id);


--
-- Name: causer; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX causer ON public.activity_log USING btree (causer_type, causer_id);


--
-- Name: countries_sort_key_de_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX countries_sort_key_de_index ON public.countries USING btree (sort_key_de);


--
-- Name: countries_sort_key_en_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX countries_sort_key_en_index ON public.countries USING btree (sort_key_en);


--
-- Name: country_regions_sort_key_de_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX country_regions_sort_key_de_index ON public.country_regions USING btree (sort_key_de);


--
-- Name: country_regions_sort_key_en_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX country_regions_sort_key_en_index ON public.country_regions USING btree (sort_key_en);


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
-- Name: events_event_type_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX events_event_type_index ON public.events USING btree (event_type);


--
-- Name: events_sort_key_de_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX events_sort_key_de_index ON public.events USING btree (sort_key_de);


--
-- Name: events_sort_key_en_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX events_sort_key_en_index ON public.events USING btree (sort_key_en);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: model_has_permissions_model_id_model_type_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX model_has_permissions_model_id_model_type_index ON public.model_has_permissions USING btree (model_id, model_type);


--
-- Name: model_has_roles_model_id_model_type_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX model_has_roles_model_id_model_type_index ON public.model_has_roles USING btree (model_id, model_type);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: telescope_entries_batch_id_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX telescope_entries_batch_id_index ON public.telescope_entries USING btree (batch_id);


--
-- Name: telescope_entries_created_at_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX telescope_entries_created_at_index ON public.telescope_entries USING btree (created_at);


--
-- Name: telescope_entries_family_hash_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX telescope_entries_family_hash_index ON public.telescope_entries USING btree (family_hash);


--
-- Name: telescope_entries_tags_tag_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX telescope_entries_tags_tag_index ON public.telescope_entries_tags USING btree (tag);


--
-- Name: telescope_entries_type_should_display_on_index_index; Type: INDEX; Schema: public; Owner: gunreip
--

CREATE INDEX telescope_entries_type_should_display_on_index_index ON public.telescope_entries USING btree (type, should_display_on_index);


--
-- Name: app_user_settings app_user_settings_user_ulid_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.app_user_settings
    ADD CONSTRAINT app_user_settings_user_ulid_foreign FOREIGN KEY (user_ulid) REFERENCES public.users(ulid) ON DELETE CASCADE;


--
-- Name: country_regions country_regions_country_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.country_regions
    ADD CONSTRAINT country_regions_country_id_foreign FOREIGN KEY (country_id) REFERENCES public.countries(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: debug_logs debug_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.debug_logs
    ADD CONSTRAINT debug_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(ulid) ON DELETE SET NULL;


--
-- Name: events events_country_region_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_country_region_id_foreign FOREIGN KEY (country_region_id) REFERENCES public.country_regions(id) ON UPDATE CASCADE ON DELETE RESTRICT;


--
-- Name: model_has_permissions model_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.model_has_permissions
    ADD CONSTRAINT model_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: model_has_roles model_has_roles_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.model_has_roles
    ADD CONSTRAINT model_has_roles_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: role_has_permissions role_has_permissions_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.role_has_permissions
    ADD CONSTRAINT role_has_permissions_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: telescope_entries_tags telescope_entries_tags_entry_uuid_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.telescope_entries_tags
    ADD CONSTRAINT telescope_entries_tags_entry_uuid_foreign FOREIGN KEY (entry_uuid) REFERENCES public.telescope_entries(uuid) ON DELETE CASCADE;


--
-- Name: ui_preferences ui_preferences_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: gunreip
--

ALTER TABLE ONLY public.ui_preferences
    ADD CONSTRAINT ui_preferences_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(ulid) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict WRYVdjA44fKYIsS1AZgHhIBqjlF01VwUPtWpLqf51zzylorhyyJsdySWYnOMfkD

