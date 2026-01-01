--
-- PostgreSQL database dump
--

\restrict 45299tG9rBpNKA3fE2ugFZVObpMjiUe4s0lr6xgv62pMvTfnDBS1Q9Et36N5nic

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
-- Data for Name: countries; Type: TABLE DATA; Schema: public; Owner: gunreip
--

COPY public.countries (id, iso_3166_2, iso_3166_3, name_en, name_de, region, subregion, currency_code, phone_code, sort_key, sort_key_de, translit_en, translit_de, created_at, updated_at) FROM stdin;
1	AL	ALB	Albania	Albanien	Europe	Southern Europe	ALL	+355	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
2	AD	AND	Andorra	Andorra	Europe	Southern Europe	EUR	+376	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
3	AT	AUT	Austria	Österreich	Europe	Western Europe	EUR	+43	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
4	BY	BLR	Belarus	Belarus	Europe	Eastern Europe	BYN	+375	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
5	BE	BEL	Belgium	Belgien	Europe	Western Europe	EUR	+32	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
6	BA	BIH	Bosnia and Herzegovina	Bosnien und Herzegowina	Europe	Southern Europe	BAM	+387	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
7	BG	BGR	Bulgaria	Bulgarien	Europe	Eastern Europe	BGN	+359	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
8	HR	HRV	Croatia	Kroatien	Europe	Southern Europe	EUR	+385	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
9	CY	CYP	Cyprus	Zypern	Europe	Western Asia	EUR	+357	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
10	CZ	CZE	Czech Republic	Tschechien	Europe	Eastern Europe	CZK	+420	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
11	DK	DNK	Denmark	Dänemark	Europe	Northern Europe	DKK	+45	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
12	EE	EST	Estonia	Estland	Europe	Northern Europe	EUR	+372	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
13	FI	FIN	Finland	Finnland	Europe	Northern Europe	EUR	+358	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
14	FR	FRA	France	Frankreich	Europe	Western Europe	EUR	+33	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
15	DE	DEU	Germany	Deutschland	Europe	Western Europe	EUR	+49	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
16	GR	GRC	Greece	Griechenland	Europe	Southern Europe	EUR	+30	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
17	HU	HUN	Hungary	Ungarn	Europe	Eastern Europe	HUF	+36	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
18	IS	ISL	Iceland	Island	Europe	Northern Europe	ISK	+354	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
19	IE	IRL	Ireland	Irland	Europe	Northern Europe	EUR	+353	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
20	IT	ITA	Italy	Italien	Europe	Southern Europe	EUR	+39	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
21	LV	LVA	Latvia	Lettland	Europe	Northern Europe	EUR	+371	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
22	LI	LIE	Liechtenstein	Liechtenstein	Europe	Western Europe	CHF	+423	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
23	LT	LTU	Lithuania	Litauen	Europe	Northern Europe	EUR	+370	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
24	LU	LUX	Luxembourg	Luxemburg	Europe	Western Europe	EUR	+352	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
25	MT	MLT	Malta	Malta	Europe	Southern Europe	EUR	+356	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
26	MD	MDA	Moldova	Moldau	Europe	Eastern Europe	MDL	+373	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
27	MC	MCO	Monaco	Monaco	Europe	Western Europe	EUR	+377	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
28	ME	MNE	Montenegro	Montenegro	Europe	Southern Europe	EUR	+382	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
29	NL	NLD	Netherlands	Niederlande	Europe	Western Europe	EUR	+31	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
30	MK	MKD	North Macedonia	Nordmazedonien	Europe	Southern Europe	MKD	+389	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
31	NO	NOR	Norway	Norwegen	Europe	Northern Europe	NOK	+47	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
32	PL	POL	Poland	Polen	Europe	Eastern Europe	PLN	+48	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
33	PT	PRT	Portugal	Portugal	Europe	Southern Europe	EUR	+351	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
34	RO	ROU	Romania	Rumänien	Europe	Eastern Europe	RON	+40	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
35	RU	RUS	Russia	Russland	Europe	Eastern Europe	RUB	+7	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
36	SM	SMR	San Marino	San Marino	Europe	Southern Europe	EUR	+378	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
37	RS	SRB	Serbia	Serbien	Europe	Southern Europe	RSD	+381	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
38	SK	SVK	Slovakia	Slowakei	Europe	Eastern Europe	EUR	+421	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
39	SI	SVN	Slovenia	Slowenien	Europe	Southern Europe	EUR	+386	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
40	ES	ESP	Spain	Spanien	Europe	Southern Europe	EUR	+34	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
41	SE	SWE	Sweden	Schweden	Europe	Northern Europe	SEK	+46	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
42	CH	CHE	Switzerland	Schweiz	Europe	Western Europe	CHF	+41	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
43	UA	UKR	Ukraine	Ukraine	Europe	Eastern Europe	UAH	+380	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
44	GB	GBR	United Kingdom	Vereinigtes Königreich	Europe	Northern Europe	GBP	+44	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
45	VA	VAT	Vatican City	Vatikanstadt	Europe	Southern Europe	EUR	+379	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
46	AG	ATG	Antigua and Barbuda	Antigua und Barbuda	Americas	Caribbean	XCD	+1-268	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
47	AR	ARG	Argentina	Argentinien	Americas	South America	ARS	+54	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
48	BS	BHS	Bahamas	Bahamas	Americas	Caribbean	BSD	+1-242	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
49	BB	BRB	Barbados	Barbados	Americas	Caribbean	BBD	+1-246	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
50	BZ	BLZ	Belize	Belize	Americas	Central America	BZD	+501	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
51	BO	BOL	Bolivia	Bolivien	Americas	South America	BOB	+591	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
52	BR	BRA	Brazil	Brasilien	Americas	South America	BRL	+55	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
53	CA	CAN	Canada	Kanada	Americas	Northern America	CAD	+1	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
54	CL	CHL	Chile	Chile	Americas	South America	CLP	+56	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
55	CO	COL	Colombia	Kolumbien	Americas	South America	COP	+57	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
56	CR	CRI	Costa Rica	Costa Rica	Americas	Central America	CRC	+506	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
57	CU	CUB	Cuba	Kuba	Americas	Caribbean	CUP	+53	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
58	DM	DMA	Dominica	Dominica	Americas	Caribbean	XCD	+1-767	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
59	DO	DOM	Dominican Republic	Dominikanische Republik	Americas	Caribbean	DOP	+1-809	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
60	EC	ECU	Ecuador	Ecuador	Americas	South America	USD	+593	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
61	SV	SLV	El Salvador	El Salvador	Americas	Central America	USD	+503	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
62	GD	GRD	Grenada	Grenada	Americas	Caribbean	XCD	+1-473	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
63	GT	GTM	Guatemala	Guatemala	Americas	Central America	GTQ	+502	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
64	GY	GUY	Guyana	Guyana	Americas	South America	GYD	+592	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
65	HT	HTI	Haiti	Haiti	Americas	Caribbean	HTG	+509	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
66	HN	HND	Honduras	Honduras	Americas	Central America	HNL	+504	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
67	JM	JAM	Jamaica	Jamaika	Americas	Caribbean	JMD	+1-876	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
68	MX	MEX	Mexico	Mexiko	Americas	North America	MXN	+52	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
69	NI	NIC	Nicaragua	Nicaragua	Americas	Central America	NIO	+505	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
70	PA	PAN	Panama	Panama	Americas	Central America	PAB	+507	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
71	PY	PRY	Paraguay	Paraguay	Americas	South America	PYG	+595	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
72	PE	PER	Peru	Peru	Americas	South America	PEN	+51	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
73	KN	KNA	Saint Kitts and Nevis	St. Kitts und Nevis	Americas	Caribbean	XCD	+1-869	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
74	LC	LCA	Saint Lucia	St. Lucia	Americas	Caribbean	XCD	+1-758	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
75	VC	VCT	Saint Vincent and the Grenadines	St. Vincent und die Grenadinen	Americas	Caribbean	XCD	+1-784	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
76	SR	SUR	Suriname	Suriname	Americas	South America	SRD	+597	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
77	TT	TTO	Trinidad and Tobago	Trinidad und Tobago	Americas	Caribbean	TTD	+1-868	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
78	UY	URY	Uruguay	Uruguay	Americas	South America	UYU	+598	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
79	VE	VEN	Venezuela	Venezuela	Americas	South America	VES	+58	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
80	US	USA	United States	Vereinigte Staaten	Americas	Northern America	USD	+1	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
81	DZ	DZA	Algeria	Algerien	Africa	Northern Africa	DZD	+213	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
82	AO	AGO	Angola	Angola	Africa	Middle Africa	AOA	+244	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
83	BJ	BEN	Benin	Benin	Africa	Western Africa	XOF	+229	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
84	BW	BWA	Botswana	Botswana	Africa	Southern Africa	BWP	+267	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
85	BF	BFA	Burkina Faso	Burkina Faso	Africa	Western Africa	XOF	+226	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
86	BI	BDI	Burundi	Burundi	Africa	Eastern Africa	BIF	+257	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
87	CM	CMR	Cameroon	Kamerun	Africa	Middle Africa	XAF	+237	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
88	CV	CPV	Cabo Verde	Kap Verde	Africa	Western Africa	CVE	+238	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
89	CF	CAF	Central African Republic	Zentralafrikanische Republik	Africa	Middle Africa	XAF	+236	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
90	TD	TCD	Chad	Tschad	Africa	Middle Africa	XAF	+235	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
91	KM	COM	Comoros	Komoren	Africa	Eastern Africa	KMF	+269	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
92	CG	COG	Republic of the Congo	Kongo (Republik)	Africa	Middle Africa	XAF	+242	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
93	CD	COD	Democratic Republic of the Congo	Kongo (Demokratische Republik)	Africa	Middle Africa	CDF	+243	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
94	CI	CIV	Ivory Coast	Elfenbeinküste	Africa	Western Africa	XOF	+225	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
95	DJ	DJI	Djibouti	Dschibuti	Africa	Eastern Africa	DJF	+253	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
96	EG	EGY	Egypt	Ägypten	Africa	Northern Africa	EGP	+20	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
97	GQ	GNQ	Equatorial Guinea	Äquatorialguinea	Africa	Middle Africa	XAF	+240	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
98	ER	ERI	Eritrea	Eritrea	Africa	Eastern Africa	ERN	+291	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
99	SZ	SWZ	Eswatini	Eswatini	Africa	Southern Africa	SZL	+268	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
100	ET	ETH	Ethiopia	Äthiopien	Africa	Eastern Africa	ETB	+251	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
101	GA	GAB	Gabon	Gabun	Africa	Middle Africa	XAF	+241	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
102	GM	GMB	The Gambia	Gambia	Africa	Western Africa	GMD	+220	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
103	GH	GHA	Ghana	Ghana	Africa	Western Africa	GHS	+233	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
104	GN	GIN	Guinea	Guinea	Africa	Western Africa	GNF	+224	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
105	GW	GNB	Guinea-Bissau	Guinea-Bissau	Africa	Western Africa	XOF	+245	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
106	KE	KEN	Kenya	Kenia	Africa	Eastern Africa	KES	+254	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
107	LS	LSO	Lesotho	Lesotho	Africa	Southern Africa	LSL	+266	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
108	LR	LBR	Liberia	Liberia	Africa	Western Africa	LRD	+231	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
109	LY	LBY	Libya	Libyen	Africa	Northern Africa	LYD	+218	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
110	MG	MDG	Madagascar	Madagaskar	Africa	Eastern Africa	MGA	+261	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
111	MW	MWI	Malawi	Malawi	Africa	Eastern Africa	MWK	+265	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
112	ML	MLI	Mali	Mali	Africa	Western Africa	XOF	+223	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
113	MR	MRT	Mauritania	Mauretanien	Africa	Western Africa	MRU	+222	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
114	MU	MUS	Mauritius	Mauritius	Africa	Eastern Africa	MUR	+230	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
115	MA	MAR	Morocco	Marokko	Africa	Northern Africa	MAD	+212	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
116	MZ	MOZ	Mozambique	Mosambik	Africa	Eastern Africa	MZN	+258	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
117	NA	NAM	Namibia	Namibia	Africa	Southern Africa	NAD	+264	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
118	NE	NER	Niger	Niger	Africa	Western Africa	XOF	+227	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
119	NG	NGA	Nigeria	Nigeria	Africa	Western Africa	NGN	+234	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
120	RW	RWA	Rwanda	Ruanda	Africa	Eastern Africa	RWF	+250	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
121	ST	STP	São Tomé and Príncipe	São Tomé und Príncipe	Africa	Middle Africa	STN	+239	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
122	SN	SEN	Senegal	Senegal	Africa	Western Africa	XOF	+221	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
123	SC	SYC	Seychelles	Seychellen	Africa	Eastern Africa	SCR	+248	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
124	SL	SLE	Sierra Leone	Sierra Leone	Africa	Western Africa	SLL	+232	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
125	SO	SOM	Somalia	Somalia	Africa	Eastern Africa	SOS	+252	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
126	ZA	ZAF	South Africa	Südafrika	Africa	Southern Africa	ZAR	+27	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
127	SS	SSD	South Sudan	Südsudan	Africa	Eastern Africa	SSP	+211	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
128	SD	SDN	Sudan	Sudan	Africa	Northern Africa	SDG	+249	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
129	TZ	TZA	Tanzania	Tansania	Africa	Eastern Africa	TZS	+255	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
130	TG	TGO	Togo	Togo	Africa	Western Africa	XOF	+228	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
131	TN	TUN	Tunisia	Tunesien	Africa	Northern Africa	TND	+216	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
132	UG	UGA	Uganda	Uganda	Africa	Eastern Africa	UGX	+256	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
133	ZM	ZMB	Zambia	Zambia	Africa	Eastern Africa	ZMW	+260	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
134	ZW	ZWE	Zimbabwe	Zimbabwe	Africa	Eastern Africa	ZWL	+263	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
135	AF	AFG	Afghanistan	Afghanistan	Asia	Southern Asia	AFN	+93	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
136	AM	ARM	Armenia	Armenien	Asia	Western Asia	AMD	+374	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
137	AZ	AZE	Azerbaijan	Aserbaidschan	Asia	Western Asia	AZN	+994	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
138	BH	BHR	Bahrain	Bahrain	Asia	Western Asia	BHD	+973	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
139	BD	BGD	Bangladesh	Bangladesch	Asia	Southern Asia	BDT	+880	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
140	BT	BTN	Bhutan	Bhutan	Asia	Southern Asia	BTN	+975	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
141	BN	BRN	Brunei	Brunei	Asia	South-Eastern Asia	BND	+673	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
142	KH	KHM	Cambodia	Kambodscha	Asia	South-Eastern Asia	KHR	+855	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
143	CN	CHN	China	China	Asia	Eastern Asia	CNY	+86	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
144	GE	GEO	Georgia	Georgien	Asia	Western Asia	GEL	+995	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
145	HK	HKG	Hong Kong	Hongkong	Asia	Eastern Asia	HKD	+852	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
146	IN	IND	India	Indien	Asia	Southern Asia	INR	+91	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
147	ID	IDN	Indonesia	Indonesien	Asia	South-Eastern Asia	IDR	+62	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
148	IR	IRN	Iran	Iran	Asia	Southern Asia	IRR	+98	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
149	IQ	IRQ	Iraq	Irak	Asia	Western Asia	IQD	+964	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
150	IL	ISR	Israel	Israel	Asia	Western Asia	ILS	+972	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
151	JP	JPN	Japan	Japan	Asia	Eastern Asia	JPY	+81	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
152	JO	JOR	Jordan	Jordanien	Asia	Western Asia	JOD	+962	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
153	KZ	KAZ	Kazakhstan	Kasachstan	Asia	Central Asia	KZT	+7	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
154	KW	KWT	Kuwait	Kuwait	Asia	Western Asia	KWD	+965	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
155	KG	KGZ	Kyrgyzstan	Kirgisistan	Asia	Central Asia	KGS	+996	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
156	LA	LAO	Laos	Laos	Asia	South-Eastern Asia	LAK	+856	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
157	LB	LBN	Lebanon	Libanon	Asia	Western Asia	LBP	+961	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
158	MO	MAC	Macau	Macau	Asia	Eastern Asia	MOP	+853	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
159	MY	MYS	Malaysia	Malaysia	Asia	South-Eastern Asia	MYR	+60	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
160	MV	MDV	Maldives	Malediven	Asia	Southern Asia	MVR	+960	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
161	MN	MNG	Mongolia	Mongolei	Asia	Eastern Asia	MNT	+976	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
162	MM	MMR	Myanmar	Myanmar	Asia	South-Eastern Asia	MMK	+95	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
163	NP	NPL	Nepal	Nepal	Asia	Southern Asia	NPR	+977	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
164	KP	PRK	North Korea	Nordkorea	Asia	Eastern Asia	KPW	+850	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
165	OM	OMN	Oman	Oman	Asia	Western Asia	OMR	+968	\N	\N	\N	\N	2025-11-23 11:09:52	2025-11-23 11:09:52
166	PK	PAK	Pakistan	Pakistan	Asia	Southern Asia	PKR	+92	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
167	PS	PSE	Palestine	Palästina	Asia	Western Asia	\N	+970	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
168	PH	PHL	Philippines	Philippinen	Asia	South-Eastern Asia	PHP	+63	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
169	QA	QAT	Qatar	Katar	Asia	Western Asia	QAR	+974	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
170	SA	SAU	Saudi Arabia	Saudi-Arabien	Asia	Western Asia	SAR	+966	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
171	SG	SGP	Singapore	Singapur	Asia	South-Eastern Asia	SGD	+65	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
172	KR	KOR	South Korea	Südkorea	Asia	Eastern Asia	KRW	+82	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
173	LK	LKA	Sri Lanka	Sri Lanka	Asia	Southern Asia	LKR	+94	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
174	SY	SYR	Syria	Syrien	Asia	Western Asia	SYP	+963	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
175	TW	TWN	Taiwan	Taiwan	Asia	Eastern Asia	TWD	+886	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
176	TJ	TJK	Tajikistan	Tadschikistan	Asia	Central Asia	TJS	+992	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
177	TH	THA	Thailand	Thailand	Asia	South-Eastern Asia	THB	+66	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
178	TL	TLS	Timor-Leste	Osttimor	Asia	South-Eastern Asia	USD	+670	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
179	TR	TUR	Turkey	Türkei	Asia	Western Asia	TRY	+90	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
180	TM	TKM	Turkmenistan	Turkmenistan	Asia	Central Asia	TMT	+993	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
181	AE	ARE	United Arab Emirates	Vereinigte Arabische Emirate	Asia	Western Asia	AED	+971	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
182	UZ	UZB	Uzbekistan	Usbekistan	Asia	Central Asia	UZS	+998	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
183	VN	VNM	Vietnam	Vietnam	Asia	South-Eastern Asia	VND	+84	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
184	YE	YEM	Yemen	Jemen	Asia	Western Asia	YER	+967	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
185	AS	ASM	American Samoa	Amerikanisch-Samoa	Oceania	Polynesia	USD	+1-684	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
186	AU	AUS	Australia	Australien	Oceania	Australia and New Zealand	AUD	+61	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
187	CK	COK	Cook Islands	Cookinseln	Oceania	Polynesia	NZD	+682	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
188	FJ	FJI	Fiji	Fidschi	Oceania	Melanesia	FJD	+679	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
189	PF	PYF	French Polynesia	Französisch-Polynesien	Oceania	Polynesia	XPF	+689	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
190	GU	GUM	Guam	Guam	Oceania	Micronesia	USD	+1-671	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
191	KI	KIR	Kiribati	Kiribati	Oceania	Micronesia	AUD	+686	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
192	MH	MHL	Marshall Islands	Marshallinseln	Oceania	Micronesia	USD	+692	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
193	FM	FSM	Micronesia	Mikronesien	Oceania	Micronesia	USD	+691	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
194	NR	NRU	Nauru	Nauru	Oceania	Micronesia	AUD	+674	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
195	NC	NCL	New Caledonia	Neukaledonien	Oceania	Melanesia	XPF	+687	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
196	NZ	NZL	New Zealand	Neuseeland	Oceania	Australia and New Zealand	NZD	+64	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
197	NU	NIU	Niue	Niue	Oceania	Polynesia	NZD	+683	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
198	MP	MNP	Northern Mariana Islands	Nördliche Marianen	Oceania	Micronesia	USD	+1-670	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
199	PW	PLW	Palau	Palau	Oceania	Micronesia	USD	+680	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
200	PG	PNG	Papua New Guinea	Papua-Neuguinea	Oceania	Melanesia	PGK	+675	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
201	WS	WSM	Samoa	Samoa	Oceania	Polynesia	WST	+685	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
202	SB	SLB	Solomon Islands	Salomonen	Oceania	Melanesia	SBD	+677	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
203	TO	TON	Tonga	Tonga	Oceania	Polynesia	TOP	+676	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
204	TV	TUV	Tuvalu	Tuvalu	Oceania	Polynesia	AUD	+688	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
205	VU	VUT	Vanuatu	Vanuatu	Oceania	Melanesia	VUV	+678	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
206	AQ	ATA	Antarctica	Antarktis	Antarctica	\N	\N	\N	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
207	BV	BVT	Bouvet Island	Bouvetinsel	Antarctica	\N	\N	\N	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
208	TF	ATF	French Southern Territories	Französische Südgebiete	Antarctica	\N	EUR	\N	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
209	HM	HMD	Heard Island and McDonald Islands	Heard und McDonaldinseln	Antarctica	\N	AUD	\N	\N	\N	\N	\N	2025-11-23 11:09:53	2025-11-23 11:09:53
\.


--
-- Name: countries_id_seq; Type: SEQUENCE SET; Schema: public; Owner: gunreip
--

SELECT pg_catalog.setval('public.countries_id_seq', 209, true);


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

\unrestrict 45299tG9rBpNKA3fE2ugFZVObpMjiUe4s0lr6xgv62pMvTfnDBS1Q9Et36N5nic

