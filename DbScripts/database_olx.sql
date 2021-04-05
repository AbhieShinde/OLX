-- 
-- Creating Tables
--

CREATE TABLE public.advertisement_comments (
    id integer NOT NULL,
    advertisement_id integer NOT NULL,
    comment_type_id integer NOT NULL,
    message character varying,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_by integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    created_by integer NOT NULL
);

CREATE TABLE public.advertisements (
    id integer NOT NULL,
    title character varying NOT NULL,
    description character varying NOT NULL,
    price integer NOT NULL,
    advertisement_status_type_id integer DEFAULT 0 NOT NULL,
    product_category_id integer NOT NULL,
    product_status_id integer DEFAULT 0 NOT NULL,
    amount integer,
    purchased_date date,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_by integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    created_by integer NOT NULL
);

CREATE TABLE public.comment_types (
    id integer NOT NULL,
    name character varying NOT NULL
);

CREATE TABLE public.media (
    id integer NOT NULL,
    file_size character varying NOT NULL,
    file_name character varying NOT NULL,
    file_path character varying NOT NULL,
    advertisement_id integer NOT NULL,
    product_category_id integer NOT NULL,
    created_at timestamp with time zone DEFAULT now(),
    created_by integer NOT NULL
);

CREATE TABLE public.product_categories (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    updated_at timestamp with time zone DEFAULT now(),
    updated_by integer DEFAULT 0 NOT NULL,
    created_at timestamp with time zone DEFAULT now(),
    created_by integer DEFAULT 0 NOT NULL
);

CREATE TABLE public.product_statuses (
    id integer NOT NULL,
    name character varying(100) NOT NULL
);

CREATE TABLE advertisement_status_types (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description character varying NOT NULL
);

CREATE TABLE public.users (
    id integer NOT NULL,
    name character varying NOT NULL,
    email character varying NOT NULL,
    password character varying NOT NULL,
    hash character varying,
    is_admin boolean DEFAULT false NOT NULL,
    phone bigint NOT NULL,
    city character varying NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_by integer NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL
);

-- logs
CREATE SCHEMA logs
    AUTHORIZATION postgres;

CREATE TABLE logs.emails (
    id integer NOT NULL,
    user_id integer NOT NULL,
    email_type_id integer NOT NULL,
	from_address character varying NOT NULL,
	to_address character varying NOT NULL,
	subject character varying NOT NULL,
	body character varying NOT NULL,
	attachment_path character varying,
	is_sent BOOLEAN NOT NULL,
    details character varying,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    created_by integer NOT NULL
);

CREATE TABLE logs.email_types (
    id integer NOT NULL,
    name character varying(100) NOT NULL,
    description character varying
);

CREATE TABLE logs.media (
    id integer NOT NULL,
	media_id integer NOT NULL,
    file_size character varying NOT NULL,
    file_name character varying NOT NULL,
    file_path character varying NOT NULL,
    advertisement_id integer NOT NULL,
    product_category_id integer NOT NULL,
    created_at timestamp with time zone DEFAULT now(),
    created_by integer NOT NULL
);

--
-- Adding Sequences for IDs
--

CREATE SEQUENCE public."users_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE public."users_id_seq" OWNED BY public.users.id;
ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public."users_id_seq"'::regclass);

CREATE SEQUENCE public."advertisement_comments_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE public."advertisement_comments_id_seq" OWNED BY public.advertisement_comments.id;
ALTER TABLE ONLY public.advertisement_comments ALTER COLUMN id SET DEFAULT nextval('public."advertisement_comments_id_seq"'::regclass);

CREATE SEQUENCE public."advertisements_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE public."advertisements_id_seq" OWNED BY public.advertisements.id;
ALTER TABLE ONLY public.advertisements ALTER COLUMN id SET DEFAULT nextval('public."advertisements_id_seq"'::regclass);

CREATE SEQUENCE public."media_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE public."media_id_seq" OWNED BY public.media.id;
ALTER TABLE ONLY public.media ALTER COLUMN id SET DEFAULT nextval('public."media_id_seq"'::regclass);

CREATE SEQUENCE public."category_id_seq"
    START WITH 6
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE public."category_id_seq" OWNED BY public.product_categories.id;
ALTER TABLE ONLY public.product_categories ALTER COLUMN id SET DEFAULT nextval('public."category_id_seq"'::regclass);

-- logs
CREATE SEQUENCE logs."email_id_seq"
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE;
ALTER SEQUENCE logs."email_id_seq" OWNED BY logs.emails.id;
ALTER TABLE ONLY logs.emails ALTER COLUMN id SET DEFAULT nextval('logs."email_id_seq"'::regclass);

-- 
-- Adding Default entries
--

INSERT INTO public.comment_types (id, name) VALUES
(0,	'like'),
(1,	'dislike'),
(2,	'message');

INSERT INTO public.product_categories (id, name) VALUES
(0,	'Electronics'),
(1,	'Household Goods'),
(2,	'Furniture'),
(3,	'Cars'),
(4,	'Bikes'),
(5,	'Others');

INSERT INTO public.product_statuses (id, name) VALUES	
(0,	'For Sale'),
(1,	'Sold'),
(2,	'Not for Sale');

INSERT INTO public.advertisement_status_types (id, name, description) VALUES	
(0,	'Pending', 'Pending for Review'),
(1,	'Approved', 'Approved for publish'),
(2,	'Rejected', 'Refused for publish');

INSERT INTO public.users (id, name, email, password, is_admin, phone, city, updated_by) VALUES
(0,	'Admin - AbhieShinde', 'admin@abhieshinde.tech',	'5e5bc4663247ffd2c5af0d93a1e392f9992bbff7e983c4b47e1e807bb5a573f856211244994af2d632bcb92cee9fb0a73af0b43cb1d65f4f801671515d866353', 'true', 1234567890,	'Pune', 0);

-- 
-- Adding Constraints
--

ALTER TABLE ONLY public.advertisement_comments
    ADD CONSTRAINT advertisement_comments_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_id PRIMARY KEY (id);

ALTER TABLE ONLY public.comment_types
    ADD CONSTRAINT comment_types_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.product_categories
    ADD CONSTRAINT id PRIMARY KEY (id);

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.product_statuses
    ADD CONSTRAINT product_statuses_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.advertisement_status_types
    ADD CONSTRAINT advertisement_status_types_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_ukey_email UNIQUE (email);

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);

ALTER TABLE ONLY logs.email_types
    ADD CONSTRAINT email_types_pkey PRIMARY KEY (id);

ALTER TABLE ONLY public.advertisement_comments
    ADD CONSTRAINT advertisement_comments_created_by_fkey FOREIGN KEY (created_by) REFERENCES public.users(id);

ALTER TABLE ONLY public.advertisement_comments
    ADD CONSTRAINT advertisement_comments_fkey_advertisement_id FOREIGN KEY (advertisement_id) REFERENCES public.advertisements(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisement_comments
    ADD CONSTRAINT advertisement_comments_fkey_comment_types_id FOREIGN KEY (comment_type_id) REFERENCES public.comment_types(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisement_comments
    ADD CONSTRAINT advertisement_comments_updated_by_fkey FOREIGN KEY (updated_by) REFERENCES public.users(id);

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_fkey1_user_id FOREIGN KEY (created_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_fkey_product_categoty_id FOREIGN KEY (product_category_id) REFERENCES public.product_categories(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_fkey_product_statuses_id FOREIGN KEY (product_status_id) REFERENCES public.product_statuses(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_fkey_user_id FOREIGN KEY (updated_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.advertisements
    ADD CONSTRAINT advertisements_fkey_status_type_id FOREIGN KEY (advertisement_status_type_id) REFERENCES public.advertisement_status_types(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_fkey_advertisement_id FOREIGN KEY (advertisement_id) REFERENCES public.advertisements(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_fkey_product_category_id FOREIGN KEY (product_category_id) REFERENCES public.product_categories(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.media
    ADD CONSTRAINT media_fkey_user_id FOREIGN KEY (created_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.product_categories
    ADD CONSTRAINT product_categories_fkey1_user_id FOREIGN KEY (created_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.product_categories
    ADD CONSTRAINT product_categories_fkey_user_id FOREIGN KEY (updated_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_updated_by_fkey FOREIGN KEY (updated_by) REFERENCES public.users(id) ON UPDATE CASCADE ON DELETE CASCADE;

-- logs
ALTER TABLE logs.emails
    ADD CONSTRAINT log_email_fkey_email_type_id FOREIGN KEY (email_type_id)
    REFERENCES logs.email_types (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
CREATE INDEX fki_log_email_fkey_email_type_id
    ON logs.emails(email_type_id);

ALTER TABLE logs.emails
    ADD CONSTRAINT log_email_fkey_user_id FOREIGN KEY (user_id)
    REFERENCES public.users (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
CREATE INDEX fki_log_email_fkey_user_id
    ON logs.emails(user_id);

CREATE INDEX media_log_id
    ON logs.media(id, advertisement_id, media_id);

--
-- PostgreSQL Database for ABHIESHINDE_OLX system
-- Abhishek Shinde
--
