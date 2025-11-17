--
-- PostgreSQL database dump
--

\restrict xkaOCFk2Mnne3rE9PhlUVQLJgFjj6Wl6Y7tfohwLyM0bLtWN3NZwaV3HAkfriBb

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

COPY public.countries (id, iso_3166_2, iso_3166_3, name_en, name_de, region, subregion, currency_code, phone_code, created_at, updated_at) FROM stdin;
1	AL	ALB	Albania	Albanien	Europe	Southern Europe	ALL	+355	2025-11-13 19:34:25	2025-11-13 19:34:25
2	AD	AND	Andorra	Andorra	Europe	Southern Europe	EUR	+376	2025-11-13 19:34:25	2025-11-13 19:34:25
3	AT	AUT	Austria	Österreich	Europe	Western Europe	EUR	+43	2025-11-13 19:34:25	2025-11-13 19:34:25
4	BY	BLR	Belarus	Belarus	Europe	Eastern Europe	BYN	+375	2025-11-13 19:34:25	2025-11-13 19:34:25
5	BE	BEL	Belgium	Belgien	Europe	Western Europe	EUR	+32	2025-11-13 19:34:25	2025-11-13 19:34:25
6	BA	BIH	Bosnia and Herzegovina	Bosnien und Herzegowina	Europe	Southern Europe	BAM	+387	2025-11-13 19:34:25	2025-11-13 19:34:25
7	BG	BGR	Bulgaria	Bulgarien	Europe	Eastern Europe	BGN	+359	2025-11-13 19:34:25	2025-11-13 19:34:25
8	HR	HRV	Croatia	Kroatien	Europe	Southern Europe	EUR	+385	2025-11-13 19:34:25	2025-11-13 19:34:25
9	CY	CYP	Cyprus	Zypern	Europe	Western Asia	EUR	+357	2025-11-13 19:34:25	2025-11-13 19:34:25
10	CZ	CZE	Czech Republic	Tschechien	Europe	Eastern Europe	CZK	+420	2025-11-13 19:34:25	2025-11-13 19:34:25
11	DK	DNK	Denmark	Dänemark	Europe	Northern Europe	DKK	+45	2025-11-13 19:34:25	2025-11-13 19:34:25
12	EE	EST	Estonia	Estland	Europe	Northern Europe	EUR	+372	2025-11-13 19:34:25	2025-11-13 19:34:25
13	FI	FIN	Finland	Finnland	Europe	Northern Europe	EUR	+358	2025-11-13 19:34:25	2025-11-13 19:34:25
14	FR	FRA	France	Frankreich	Europe	Western Europe	EUR	+33	2025-11-13 19:34:25	2025-11-13 19:34:25
15	DE	DEU	Germany	Deutschland	Europe	Western Europe	EUR	+49	2025-11-13 19:34:25	2025-11-13 19:34:25
16	GR	GRC	Greece	Griechenland	Europe	Southern Europe	EUR	+30	2025-11-13 19:34:25	2025-11-13 19:34:25
17	HU	HUN	Hungary	Ungarn	Europe	Eastern Europe	HUF	+36	2025-11-13 19:34:25	2025-11-13 19:34:25
18	IS	ISL	Iceland	Island	Europe	Northern Europe	ISK	+354	2025-11-13 19:34:25	2025-11-13 19:34:25
19	IE	IRL	Ireland	Irland	Europe	Northern Europe	EUR	+353	2025-11-13 19:34:25	2025-11-13 19:34:25
20	IT	ITA	Italy	Italien	Europe	Southern Europe	EUR	+39	2025-11-13 19:34:25	2025-11-13 19:34:25
21	LV	LVA	Latvia	Lettland	Europe	Northern Europe	EUR	+371	2025-11-13 19:34:25	2025-11-13 19:34:25
22	LI	LIE	Liechtenstein	Liechtenstein	Europe	Western Europe	CHF	+423	2025-11-13 19:34:25	2025-11-13 19:34:25
23	LT	LTU	Lithuania	Litauen	Europe	Northern Europe	EUR	+370	2025-11-13 19:34:25	2025-11-13 19:34:25
24	LU	LUX	Luxembourg	Luxemburg	Europe	Western Europe	EUR	+352	2025-11-13 19:34:25	2025-11-13 19:34:25
25	MT	MLT	Malta	Malta	Europe	Southern Europe	EUR	+356	2025-11-13 19:34:25	2025-11-13 19:34:25
26	MD	MDA	Moldova	Moldau	Europe	Eastern Europe	MDL	+373	2025-11-13 19:34:25	2025-11-13 19:34:25
27	MC	MCO	Monaco	Monaco	Europe	Western Europe	EUR	+377	2025-11-13 19:34:25	2025-11-13 19:34:25
28	ME	MNE	Montenegro	Montenegro	Europe	Southern Europe	EUR	+382	2025-11-13 19:34:25	2025-11-13 19:34:25
29	NL	NLD	Netherlands	Niederlande	Europe	Western Europe	EUR	+31	2025-11-13 19:34:25	2025-11-13 19:34:25
30	MK	MKD	North Macedonia	Nordmazedonien	Europe	Southern Europe	MKD	+389	2025-11-13 19:34:25	2025-11-13 19:34:25
31	NO	NOR	Norway	Norwegen	Europe	Northern Europe	NOK	+47	2025-11-13 19:34:25	2025-11-13 19:34:25
32	PL	POL	Poland	Polen	Europe	Eastern Europe	PLN	+48	2025-11-13 19:34:25	2025-11-13 19:34:25
33	PT	PRT	Portugal	Portugal	Europe	Southern Europe	EUR	+351	2025-11-13 19:34:25	2025-11-13 19:34:25
34	RO	ROU	Romania	Rumänien	Europe	Eastern Europe	RON	+40	2025-11-13 19:34:26	2025-11-13 19:34:26
35	RU	RUS	Russia	Russland	Europe	Eastern Europe	RUB	+7	2025-11-13 19:34:26	2025-11-13 19:34:26
36	SM	SMR	San Marino	San Marino	Europe	Southern Europe	EUR	+378	2025-11-13 19:34:26	2025-11-13 19:34:26
37	RS	SRB	Serbia	Serbien	Europe	Southern Europe	RSD	+381	2025-11-13 19:34:26	2025-11-13 19:34:26
38	SK	SVK	Slovakia	Slowakei	Europe	Eastern Europe	EUR	+421	2025-11-13 19:34:26	2025-11-13 19:34:26
39	SI	SVN	Slovenia	Slowenien	Europe	Southern Europe	EUR	+386	2025-11-13 19:34:26	2025-11-13 19:34:26
40	ES	ESP	Spain	Spanien	Europe	Southern Europe	EUR	+34	2025-11-13 19:34:26	2025-11-13 19:34:26
41	SE	SWE	Sweden	Schweden	Europe	Northern Europe	SEK	+46	2025-11-13 19:34:26	2025-11-13 19:34:26
42	CH	CHE	Switzerland	Schweiz	Europe	Western Europe	CHF	+41	2025-11-13 19:34:26	2025-11-13 19:34:26
43	UA	UKR	Ukraine	Ukraine	Europe	Eastern Europe	UAH	+380	2025-11-13 19:34:26	2025-11-13 19:34:26
44	GB	GBR	United Kingdom	Vereinigtes Königreich	Europe	Northern Europe	GBP	+44	2025-11-13 19:34:26	2025-11-13 19:34:26
45	VA	VAT	Vatican City	Vatikanstadt	Europe	Southern Europe	EUR	+379	2025-11-13 19:34:26	2025-11-13 19:34:26
46	AG	ATG	Antigua and Barbuda	Antigua und Barbuda	Americas	Caribbean	XCD	+1-268	2025-11-13 19:34:26	2025-11-13 19:34:26
47	AR	ARG	Argentina	Argentinien	Americas	South America	ARS	+54	2025-11-13 19:34:26	2025-11-13 19:34:26
48	BS	BHS	Bahamas	Bahamas	Americas	Caribbean	BSD	+1-242	2025-11-13 19:34:26	2025-11-13 19:34:26
49	BB	BRB	Barbados	Barbados	Americas	Caribbean	BBD	+1-246	2025-11-13 19:34:26	2025-11-13 19:34:26
50	BZ	BLZ	Belize	Belize	Americas	Central America	BZD	+501	2025-11-13 19:34:26	2025-11-13 19:34:26
51	BO	BOL	Bolivia	Bolivien	Americas	South America	BOB	+591	2025-11-13 19:34:26	2025-11-13 19:34:26
52	BR	BRA	Brazil	Brasilien	Americas	South America	BRL	+55	2025-11-13 19:34:26	2025-11-13 19:34:26
53	CA	CAN	Canada	Kanada	Americas	Northern America	CAD	+1	2025-11-13 19:34:26	2025-11-13 19:34:26
54	CL	CHL	Chile	Chile	Americas	South America	CLP	+56	2025-11-13 19:34:26	2025-11-13 19:34:26
55	CO	COL	Colombia	Kolumbien	Americas	South America	COP	+57	2025-11-13 19:34:26	2025-11-13 19:34:26
56	CR	CRI	Costa Rica	Costa Rica	Americas	Central America	CRC	+506	2025-11-13 19:34:26	2025-11-13 19:34:26
57	CU	CUB	Cuba	Kuba	Americas	Caribbean	CUP	+53	2025-11-13 19:34:26	2025-11-13 19:34:26
58	DM	DMA	Dominica	Dominica	Americas	Caribbean	XCD	+1-767	2025-11-13 19:34:26	2025-11-13 19:34:26
59	DO	DOM	Dominican Republic	Dominikanische Republik	Americas	Caribbean	DOP	+1-809	2025-11-13 19:34:26	2025-11-13 19:34:26
60	EC	ECU	Ecuador	Ecuador	Americas	South America	USD	+593	2025-11-13 19:34:26	2025-11-13 19:34:26
61	SV	SLV	El Salvador	El Salvador	Americas	Central America	USD	+503	2025-11-13 19:34:26	2025-11-13 19:34:26
62	GD	GRD	Grenada	Grenada	Americas	Caribbean	XCD	+1-473	2025-11-13 19:34:26	2025-11-13 19:34:26
63	GT	GTM	Guatemala	Guatemala	Americas	Central America	GTQ	+502	2025-11-13 19:34:26	2025-11-13 19:34:26
64	GY	GUY	Guyana	Guyana	Americas	South America	GYD	+592	2025-11-13 19:34:26	2025-11-13 19:34:26
65	HT	HTI	Haiti	Haiti	Americas	Caribbean	HTG	+509	2025-11-13 19:34:26	2025-11-13 19:34:26
66	HN	HND	Honduras	Honduras	Americas	Central America	HNL	+504	2025-11-13 19:34:26	2025-11-13 19:34:26
67	JM	JAM	Jamaica	Jamaika	Americas	Caribbean	JMD	+1-876	2025-11-13 19:34:26	2025-11-13 19:34:26
68	MX	MEX	Mexico	Mexiko	Americas	North America	MXN	+52	2025-11-13 19:34:26	2025-11-13 19:34:26
69	NI	NIC	Nicaragua	Nicaragua	Americas	Central America	NIO	+505	2025-11-13 19:34:26	2025-11-13 19:34:26
70	PA	PAN	Panama	Panama	Americas	Central America	PAB	+507	2025-11-13 19:34:26	2025-11-13 19:34:26
71	PY	PRY	Paraguay	Paraguay	Americas	South America	PYG	+595	2025-11-13 19:34:26	2025-11-13 19:34:26
72	PE	PER	Peru	Peru	Americas	South America	PEN	+51	2025-11-13 19:34:26	2025-11-13 19:34:26
73	KN	KNA	Saint Kitts and Nevis	St. Kitts und Nevis	Americas	Caribbean	XCD	+1-869	2025-11-13 19:34:26	2025-11-13 19:34:26
74	LC	LCA	Saint Lucia	St. Lucia	Americas	Caribbean	XCD	+1-758	2025-11-13 19:34:26	2025-11-13 19:34:26
75	VC	VCT	Saint Vincent and the Grenadines	St. Vincent und die Grenadinen	Americas	Caribbean	XCD	+1-784	2025-11-13 19:34:26	2025-11-13 19:34:26
76	SR	SUR	Suriname	Suriname	Americas	South America	SRD	+597	2025-11-13 19:34:26	2025-11-13 19:34:26
77	TT	TTO	Trinidad and Tobago	Trinidad und Tobago	Americas	Caribbean	TTD	+1-868	2025-11-13 19:34:26	2025-11-13 19:34:26
78	UY	URY	Uruguay	Uruguay	Americas	South America	UYU	+598	2025-11-13 19:34:26	2025-11-13 19:34:26
79	VE	VEN	Venezuela	Venezuela	Americas	South America	VES	+58	2025-11-13 19:34:26	2025-11-13 19:34:26
80	US	USA	United States	Vereinigte Staaten	Americas	Northern America	USD	+1	2025-11-13 19:34:26	2025-11-13 19:34:26
81	DZ	DZA	Algeria	Algerien	Africa	Northern Africa	DZD	+213	2025-11-13 19:34:26	2025-11-13 19:34:26
82	AO	AGO	Angola	Angola	Africa	Middle Africa	AOA	+244	2025-11-13 19:34:26	2025-11-13 19:34:26
83	BJ	BEN	Benin	Benin	Africa	Western Africa	XOF	+229	2025-11-13 19:34:26	2025-11-13 19:34:26
84	BW	BWA	Botswana	Botswana	Africa	Southern Africa	BWP	+267	2025-11-13 19:34:26	2025-11-13 19:34:26
85	BF	BFA	Burkina Faso	Burkina Faso	Africa	Western Africa	XOF	+226	2025-11-13 19:34:26	2025-11-13 19:34:26
86	BI	BDI	Burundi	Burundi	Africa	Eastern Africa	BIF	+257	2025-11-13 19:34:26	2025-11-13 19:34:26
87	CM	CMR	Cameroon	Kamerun	Africa	Middle Africa	XAF	+237	2025-11-13 19:34:26	2025-11-13 19:34:26
88	CV	CPV	Cabo Verde	Kap Verde	Africa	Western Africa	CVE	+238	2025-11-13 19:34:26	2025-11-13 19:34:26
89	CF	CAF	Central African Republic	Zentralafrikanische Republik	Africa	Middle Africa	XAF	+236	2025-11-13 19:34:26	2025-11-13 19:34:26
90	TD	TCD	Chad	Tschad	Africa	Middle Africa	XAF	+235	2025-11-13 19:34:26	2025-11-13 19:34:26
91	KM	COM	Comoros	Komoren	Africa	Eastern Africa	KMF	+269	2025-11-13 19:34:26	2025-11-13 19:34:26
92	CG	COG	Republic of the Congo	Kongo (Republik)	Africa	Middle Africa	XAF	+242	2025-11-13 19:34:26	2025-11-13 19:34:26
93	CD	COD	Democratic Republic of the Congo	Kongo (Demokratische Republik)	Africa	Middle Africa	CDF	+243	2025-11-13 19:34:26	2025-11-13 19:34:26
94	CI	CIV	Ivory Coast	Elfenbeinküste	Africa	Western Africa	XOF	+225	2025-11-13 19:34:26	2025-11-13 19:34:26
95	DJ	DJI	Djibouti	Dschibuti	Africa	Eastern Africa	DJF	+253	2025-11-13 19:34:26	2025-11-13 19:34:26
96	EG	EGY	Egypt	Ägypten	Africa	Northern Africa	EGP	+20	2025-11-13 19:34:26	2025-11-13 19:34:26
97	GQ	GNQ	Equatorial Guinea	Äquatorialguinea	Africa	Middle Africa	XAF	+240	2025-11-13 19:34:26	2025-11-13 19:34:26
98	ER	ERI	Eritrea	Eritrea	Africa	Eastern Africa	ERN	+291	2025-11-13 19:34:26	2025-11-13 19:34:26
99	SZ	SWZ	Eswatini	Eswatini	Africa	Southern Africa	SZL	+268	2025-11-13 19:34:26	2025-11-13 19:34:26
100	ET	ETH	Ethiopia	Äthiopien	Africa	Eastern Africa	ETB	+251	2025-11-13 19:34:26	2025-11-13 19:34:26
101	GA	GAB	Gabon	Gabun	Africa	Middle Africa	XAF	+241	2025-11-13 19:34:26	2025-11-13 19:34:26
102	GM	GMB	The Gambia	Gambia	Africa	Western Africa	GMD	+220	2025-11-13 19:34:26	2025-11-13 19:34:26
103	GH	GHA	Ghana	Ghana	Africa	Western Africa	GHS	+233	2025-11-13 19:34:26	2025-11-13 19:34:26
104	GN	GIN	Guinea	Guinea	Africa	Western Africa	GNF	+224	2025-11-13 19:34:26	2025-11-13 19:34:26
105	GW	GNB	Guinea-Bissau	Guinea-Bissau	Africa	Western Africa	XOF	+245	2025-11-13 19:34:26	2025-11-13 19:34:26
106	KE	KEN	Kenya	Kenia	Africa	Eastern Africa	KES	+254	2025-11-13 19:34:26	2025-11-13 19:34:26
107	LS	LSO	Lesotho	Lesotho	Africa	Southern Africa	LSL	+266	2025-11-13 19:34:26	2025-11-13 19:34:26
108	LR	LBR	Liberia	Liberia	Africa	Western Africa	LRD	+231	2025-11-13 19:34:26	2025-11-13 19:34:26
109	LY	LBY	Libya	Libyen	Africa	Northern Africa	LYD	+218	2025-11-13 19:34:26	2025-11-13 19:34:26
110	MG	MDG	Madagascar	Madagaskar	Africa	Eastern Africa	MGA	+261	2025-11-13 19:34:26	2025-11-13 19:34:26
111	MW	MWI	Malawi	Malawi	Africa	Eastern Africa	MWK	+265	2025-11-13 19:34:26	2025-11-13 19:34:26
112	ML	MLI	Mali	Mali	Africa	Western Africa	XOF	+223	2025-11-13 19:34:26	2025-11-13 19:34:26
113	MR	MRT	Mauritania	Mauretanien	Africa	Western Africa	MRU	+222	2025-11-13 19:34:26	2025-11-13 19:34:26
114	MU	MUS	Mauritius	Mauritius	Africa	Eastern Africa	MUR	+230	2025-11-13 19:34:26	2025-11-13 19:34:26
115	MA	MAR	Morocco	Marokko	Africa	Northern Africa	MAD	+212	2025-11-13 19:34:26	2025-11-13 19:34:26
116	MZ	MOZ	Mozambique	Mosambik	Africa	Eastern Africa	MZN	+258	2025-11-13 19:34:26	2025-11-13 19:34:26
117	NA	NAM	Namibia	Namibia	Africa	Southern Africa	NAD	+264	2025-11-13 19:34:26	2025-11-13 19:34:26
118	NE	NER	Niger	Niger	Africa	Western Africa	XOF	+227	2025-11-13 19:34:26	2025-11-13 19:34:26
119	NG	NGA	Nigeria	Nigeria	Africa	Western Africa	NGN	+234	2025-11-13 19:34:26	2025-11-13 19:34:26
120	RW	RWA	Rwanda	Ruanda	Africa	Eastern Africa	RWF	+250	2025-11-13 19:34:26	2025-11-13 19:34:26
121	ST	STP	São Tomé and Príncipe	São Tomé und Príncipe	Africa	Middle Africa	STN	+239	2025-11-13 19:34:26	2025-11-13 19:34:26
122	SN	SEN	Senegal	Senegal	Africa	Western Africa	XOF	+221	2025-11-13 19:34:26	2025-11-13 19:34:26
123	SC	SYC	Seychelles	Seychellen	Africa	Eastern Africa	SCR	+248	2025-11-13 19:34:26	2025-11-13 19:34:26
124	SL	SLE	Sierra Leone	Sierra Leone	Africa	Western Africa	SLL	+232	2025-11-13 19:34:26	2025-11-13 19:34:26
125	SO	SOM	Somalia	Somalia	Africa	Eastern Africa	SOS	+252	2025-11-13 19:34:26	2025-11-13 19:34:26
126	ZA	ZAF	South Africa	Südafrika	Africa	Southern Africa	ZAR	+27	2025-11-13 19:34:26	2025-11-13 19:34:26
127	SS	SSD	South Sudan	Südsudan	Africa	Eastern Africa	SSP	+211	2025-11-13 19:34:26	2025-11-13 19:34:26
128	SD	SDN	Sudan	Sudan	Africa	Northern Africa	SDG	+249	2025-11-13 19:34:26	2025-11-13 19:34:26
129	TZ	TZA	Tanzania	Tansania	Africa	Eastern Africa	TZS	+255	2025-11-13 19:34:26	2025-11-13 19:34:26
130	TG	TGO	Togo	Togo	Africa	Western Africa	XOF	+228	2025-11-13 19:34:26	2025-11-13 19:34:26
131	TN	TUN	Tunisia	Tunesien	Africa	Northern Africa	TND	+216	2025-11-13 19:34:26	2025-11-13 19:34:26
132	UG	UGA	Uganda	Uganda	Africa	Eastern Africa	UGX	+256	2025-11-13 19:34:26	2025-11-13 19:34:26
133	ZM	ZMB	Zambia	Zambia	Africa	Eastern Africa	ZMW	+260	2025-11-13 19:34:26	2025-11-13 19:34:26
134	ZW	ZWE	Zimbabwe	Zimbabwe	Africa	Eastern Africa	ZWL	+263	2025-11-13 19:34:26	2025-11-13 19:34:26
135	AF	AFG	Afghanistan	Afghanistan	Asia	Southern Asia	AFN	+93	2025-11-13 19:34:26	2025-11-13 19:34:26
136	AM	ARM	Armenia	Armenien	Asia	Western Asia	AMD	+374	2025-11-13 19:34:26	2025-11-13 19:34:26
137	AZ	AZE	Azerbaijan	Aserbaidschan	Asia	Western Asia	AZN	+994	2025-11-13 19:34:26	2025-11-13 19:34:26
138	BH	BHR	Bahrain	Bahrain	Asia	Western Asia	BHD	+973	2025-11-13 19:34:26	2025-11-13 19:34:26
139	BD	BGD	Bangladesh	Bangladesch	Asia	Southern Asia	BDT	+880	2025-11-13 19:34:26	2025-11-13 19:34:26
140	BT	BTN	Bhutan	Bhutan	Asia	Southern Asia	BTN	+975	2025-11-13 19:34:26	2025-11-13 19:34:26
141	BN	BRN	Brunei	Brunei	Asia	South-Eastern Asia	BND	+673	2025-11-13 19:34:26	2025-11-13 19:34:26
142	KH	KHM	Cambodia	Kambodscha	Asia	South-Eastern Asia	KHR	+855	2025-11-13 19:34:26	2025-11-13 19:34:26
143	CN	CHN	China	China	Asia	Eastern Asia	CNY	+86	2025-11-13 19:34:26	2025-11-13 19:34:26
144	GE	GEO	Georgia	Georgien	Asia	Western Asia	GEL	+995	2025-11-13 19:34:26	2025-11-13 19:34:26
145	HK	HKG	Hong Kong	Hongkong	Asia	Eastern Asia	HKD	+852	2025-11-13 19:34:26	2025-11-13 19:34:26
146	IN	IND	India	Indien	Asia	Southern Asia	INR	+91	2025-11-13 19:34:26	2025-11-13 19:34:26
147	ID	IDN	Indonesia	Indonesien	Asia	South-Eastern Asia	IDR	+62	2025-11-13 19:34:26	2025-11-13 19:34:26
148	IR	IRN	Iran	Iran	Asia	Southern Asia	IRR	+98	2025-11-13 19:34:26	2025-11-13 19:34:26
149	IQ	IRQ	Iraq	Irak	Asia	Western Asia	IQD	+964	2025-11-13 19:34:26	2025-11-13 19:34:26
150	IL	ISR	Israel	Israel	Asia	Western Asia	ILS	+972	2025-11-13 19:34:26	2025-11-13 19:34:26
151	JP	JPN	Japan	Japan	Asia	Eastern Asia	JPY	+81	2025-11-13 19:34:26	2025-11-13 19:34:26
152	JO	JOR	Jordan	Jordanien	Asia	Western Asia	JOD	+962	2025-11-13 19:34:26	2025-11-13 19:34:26
153	KZ	KAZ	Kazakhstan	Kasachstan	Asia	Central Asia	KZT	+7	2025-11-13 19:34:26	2025-11-13 19:34:26
154	KW	KWT	Kuwait	Kuwait	Asia	Western Asia	KWD	+965	2025-11-13 19:34:26	2025-11-13 19:34:26
155	KG	KGZ	Kyrgyzstan	Kirgisistan	Asia	Central Asia	KGS	+996	2025-11-13 19:34:26	2025-11-13 19:34:26
156	LA	LAO	Laos	Laos	Asia	South-Eastern Asia	LAK	+856	2025-11-13 19:34:26	2025-11-13 19:34:26
157	LB	LBN	Lebanon	Libanon	Asia	Western Asia	LBP	+961	2025-11-13 19:34:26	2025-11-13 19:34:26
158	MO	MAC	Macau	Macau	Asia	Eastern Asia	MOP	+853	2025-11-13 19:34:26	2025-11-13 19:34:26
159	MY	MYS	Malaysia	Malaysia	Asia	South-Eastern Asia	MYR	+60	2025-11-13 19:34:26	2025-11-13 19:34:26
160	MV	MDV	Maldives	Malediven	Asia	Southern Asia	MVR	+960	2025-11-13 19:34:26	2025-11-13 19:34:26
161	MN	MNG	Mongolia	Mongolei	Asia	Eastern Asia	MNT	+976	2025-11-13 19:34:26	2025-11-13 19:34:26
162	MM	MMR	Myanmar	Myanmar	Asia	South-Eastern Asia	MMK	+95	2025-11-13 19:34:26	2025-11-13 19:34:26
163	NP	NPL	Nepal	Nepal	Asia	Southern Asia	NPR	+977	2025-11-13 19:34:26	2025-11-13 19:34:26
164	KP	PRK	North Korea	Nordkorea	Asia	Eastern Asia	KPW	+850	2025-11-13 19:34:26	2025-11-13 19:34:26
165	OM	OMN	Oman	Oman	Asia	Western Asia	OMR	+968	2025-11-13 19:34:26	2025-11-13 19:34:26
166	PK	PAK	Pakistan	Pakistan	Asia	Southern Asia	PKR	+92	2025-11-13 19:34:26	2025-11-13 19:34:26
167	PS	PSE	Palestine	Palästina	Asia	Western Asia	\N	+970	2025-11-13 19:34:26	2025-11-13 19:34:26
168	PH	PHL	Philippines	Philippinen	Asia	South-Eastern Asia	PHP	+63	2025-11-13 19:34:26	2025-11-13 19:34:26
169	QA	QAT	Qatar	Katar	Asia	Western Asia	QAR	+974	2025-11-13 19:34:26	2025-11-13 19:34:26
170	SA	SAU	Saudi Arabia	Saudi-Arabien	Asia	Western Asia	SAR	+966	2025-11-13 19:34:26	2025-11-13 19:34:26
171	SG	SGP	Singapore	Singapur	Asia	South-Eastern Asia	SGD	+65	2025-11-13 19:34:26	2025-11-13 19:34:26
172	KR	KOR	South Korea	Südkorea	Asia	Eastern Asia	KRW	+82	2025-11-13 19:34:26	2025-11-13 19:34:26
173	LK	LKA	Sri Lanka	Sri Lanka	Asia	Southern Asia	LKR	+94	2025-11-13 19:34:26	2025-11-13 19:34:26
174	SY	SYR	Syria	Syrien	Asia	Western Asia	SYP	+963	2025-11-13 19:34:26	2025-11-13 19:34:26
175	TW	TWN	Taiwan	Taiwan	Asia	Eastern Asia	TWD	+886	2025-11-13 19:34:26	2025-11-13 19:34:26
176	TJ	TJK	Tajikistan	Tadschikistan	Asia	Central Asia	TJS	+992	2025-11-13 19:34:26	2025-11-13 19:34:26
177	TH	THA	Thailand	Thailand	Asia	South-Eastern Asia	THB	+66	2025-11-13 19:34:26	2025-11-13 19:34:26
178	TL	TLS	Timor-Leste	Osttimor	Asia	South-Eastern Asia	USD	+670	2025-11-13 19:34:26	2025-11-13 19:34:26
179	TR	TUR	Turkey	Türkei	Asia	Western Asia	TRY	+90	2025-11-13 19:34:26	2025-11-13 19:34:26
180	TM	TKM	Turkmenistan	Turkmenistan	Asia	Central Asia	TMT	+993	2025-11-13 19:34:26	2025-11-13 19:34:26
181	AE	ARE	United Arab Emirates	Vereinigte Arabische Emirate	Asia	Western Asia	AED	+971	2025-11-13 19:34:26	2025-11-13 19:34:26
182	UZ	UZB	Uzbekistan	Usbekistan	Asia	Central Asia	UZS	+998	2025-11-13 19:34:26	2025-11-13 19:34:26
183	VN	VNM	Vietnam	Vietnam	Asia	South-Eastern Asia	VND	+84	2025-11-13 19:34:26	2025-11-13 19:34:26
184	YE	YEM	Yemen	Jemen	Asia	Western Asia	YER	+967	2025-11-13 19:34:26	2025-11-13 19:34:26
185	AS	ASM	American Samoa	Amerikanisch-Samoa	Oceania	Polynesia	USD	+1-684	2025-11-13 19:34:26	2025-11-13 19:34:26
186	AU	AUS	Australia	Australien	Oceania	Australia and New Zealand	AUD	+61	2025-11-13 19:34:26	2025-11-13 19:34:26
187	CK	COK	Cook Islands	Cookinseln	Oceania	Polynesia	NZD	+682	2025-11-13 19:34:26	2025-11-13 19:34:26
188	FJ	FJI	Fiji	Fidschi	Oceania	Melanesia	FJD	+679	2025-11-13 19:34:26	2025-11-13 19:34:26
189	PF	PYF	French Polynesia	Französisch-Polynesien	Oceania	Polynesia	XPF	+689	2025-11-13 19:34:26	2025-11-13 19:34:26
190	GU	GUM	Guam	Guam	Oceania	Micronesia	USD	+1-671	2025-11-13 19:34:26	2025-11-13 19:34:26
191	KI	KIR	Kiribati	Kiribati	Oceania	Micronesia	AUD	+686	2025-11-13 19:34:26	2025-11-13 19:34:26
192	MH	MHL	Marshall Islands	Marshallinseln	Oceania	Micronesia	USD	+692	2025-11-13 19:34:26	2025-11-13 19:34:26
193	FM	FSM	Micronesia	Mikronesien	Oceania	Micronesia	USD	+691	2025-11-13 19:34:26	2025-11-13 19:34:26
194	NR	NRU	Nauru	Nauru	Oceania	Micronesia	AUD	+674	2025-11-13 19:34:26	2025-11-13 19:34:26
195	NC	NCL	New Caledonia	Neukaledonien	Oceania	Melanesia	XPF	+687	2025-11-13 19:34:26	2025-11-13 19:34:26
196	NZ	NZL	New Zealand	Neuseeland	Oceania	Australia and New Zealand	NZD	+64	2025-11-13 19:34:26	2025-11-13 19:34:26
197	NU	NIU	Niue	Niue	Oceania	Polynesia	NZD	+683	2025-11-13 19:34:26	2025-11-13 19:34:26
198	MP	MNP	Northern Mariana Islands	Nördliche Marianen	Oceania	Micronesia	USD	+1-670	2025-11-13 19:34:26	2025-11-13 19:34:26
199	PW	PLW	Palau	Palau	Oceania	Micronesia	USD	+680	2025-11-13 19:34:26	2025-11-13 19:34:26
200	PG	PNG	Papua New Guinea	Papua-Neuguinea	Oceania	Melanesia	PGK	+675	2025-11-13 19:34:26	2025-11-13 19:34:26
201	WS	WSM	Samoa	Samoa	Oceania	Polynesia	WST	+685	2025-11-13 19:34:26	2025-11-13 19:34:26
202	SB	SLB	Solomon Islands	Salomonen	Oceania	Melanesia	SBD	+677	2025-11-13 19:34:26	2025-11-13 19:34:26
203	TO	TON	Tonga	Tonga	Oceania	Polynesia	TOP	+676	2025-11-13 19:34:26	2025-11-13 19:34:26
204	TV	TUV	Tuvalu	Tuvalu	Oceania	Polynesia	AUD	+688	2025-11-13 19:34:26	2025-11-13 19:34:26
205	VU	VUT	Vanuatu	Vanuatu	Oceania	Melanesia	VUV	+678	2025-11-13 19:34:26	2025-11-13 19:34:26
206	AQ	ATA	Antarctica	Antarktis	Antarctica	\N	\N	\N	2025-11-13 19:34:26	2025-11-13 19:34:26
207	BV	BVT	Bouvet Island	Bouvetinsel	Antarctica	\N	\N	\N	2025-11-13 19:34:26	2025-11-13 19:34:26
208	TF	ATF	French Southern Territories	Französische Südgebiete	Antarctica	\N	EUR	\N	2025-11-13 19:34:26	2025-11-13 19:34:26
209	HM	HMD	Heard Island and McDonald Islands	Heard und McDonaldinseln	Antarctica	\N	AUD	\N	2025-11-13 19:34:26	2025-11-13 19:34:26
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
-- PostgreSQL database dump complete
--

\unrestrict xkaOCFk2Mnne3rE9PhlUVQLJgFjj6Wl6Y7tfohwLyM0bLtWN3NZwaV3HAkfriBb

