--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: caracteristicas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE caracteristicas (
    idcaracter integer NOT NULL,
    marca character varying(15),
    modelo character varying(15)
);


ALTER TABLE caracteristicas OWNER TO postgres;

--
-- Name: productos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE productos (
    idproducto character varying(15) NOT NULL,
    nombre character varying,
    stock_minimo numeric(12,0),
    estado smallint,
    precio_venta numeric(12,0),
    precio_costo numeric(12,0),
    stock numeric(15,0),
    idcaracter integer,
    id_iva numeric(4,2),
    idtipo integer,
    idunidad integer,
    idfactura_compra integer,
    iddescuentos numeric(4,2),
    estado_stock smallint,
    img character varying,
    utilidad_para_vendedor numeric(12,0),
    porcentaje_utilidad_vendedor numeric(4,2)
);


ALTER TABLE productos OWNER TO postgres;

--
-- Name: COLUMN productos.idfactura_compra; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN productos.idfactura_compra IS 'CUANDO EL PRODUCTO ES CREADO POR PRIMERA VEZ DEBE HABER UNA FACTURA QUE LO SUSTENTE';


--
-- Name: COLUMN productos.estado_stock; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN productos.estado_stock IS 'poner botones de colores dependiendo el stock en bodega 
/en la parte inferior de la pagina poner la explicacon de los colores/ ';


--
-- Name: registrar_compra; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE registrar_compra (
    num_factura character varying(15) NOT NULL,
    estado smallint,
    id_proveedor character varying(15) NOT NULL,
    fecha_de_compra date,
    fecha_de_pago date,
    idfactura_compra integer NOT NULL,
    nota character varying
);


ALTER TABLE registrar_compra OWNER TO postgres;

--
-- Name: COLUMN registrar_compra.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN registrar_compra.estado IS 'hacer un combox (PADAGO, PENDIENTE DE PAGO)
Y condicionar si esta pendiente de pago';


--
-- Name: COLUMN registrar_compra.fecha_de_pago; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN registrar_compra.fecha_de_pago IS 'if fue a credito la compra osea esta pendiente de pago';


--
-- Name: agotados_views; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW agotados_views AS
 SELECT productos.precio_venta,
    productos.precio_costo,
    productos.nombre,
    productos.idproducto,
    caracteristicas.marca,
    productos.img,
    registrar_compra.num_factura
   FROM productos,
    caracteristicas,
    registrar_compra
  WHERE ((((caracteristicas.idcaracter = productos.idcaracter) AND (registrar_compra.idfactura_compra = productos.idfactura_compra)) AND (productos.stock = (0)::numeric)) AND (productos.estado = 1));


ALTER TABLE agotados_views OWNER TO postgres;

--
-- Name: caracteristicas_idcaracter_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE caracteristicas_idcaracter_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE caracteristicas_idcaracter_seq OWNER TO postgres;

--
-- Name: caracteristicas_idcaracter_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE caracteristicas_idcaracter_seq OWNED BY caracteristicas.idcaracter;


--
-- Name: ciudad; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE ciudad (
    idciudad character varying(10) NOT NULL,
    nombre_ciudad character varying(100),
    iddepartamento character varying(2) NOT NULL
);


ALTER TABLE ciudad OWNER TO postgres;

--
-- Name: cliente_abono; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cliente_abono (
    nit_cc character varying(15),
    nombre text
);

ALTER TABLE ONLY cliente_abono REPLICA IDENTITY NOTHING;


ALTER TABLE cliente_abono OWNER TO postgres;

--
-- Name: cliente_saldo_vi; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE cliente_saldo_vi (
    nit_cc character varying(15),
    nombre_completo character varying(70),
    saldo numeric,
    tel_movil character varying(10),
    direccion character varying(50),
    email character varying(50),
    observacion character varying(100),
    tel_fijo character varying(10),
    idciudad character varying(10),
    idzona integer
);

ALTER TABLE ONLY cliente_saldo_vi REPLICA IDENTITY NOTHING;


ALTER TABLE cliente_saldo_vi OWNER TO postgres;

--
-- Name: clientes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE clientes (
    nit_cc character varying(15) NOT NULL,
    nombre_completo character varying(70),
    direccion character varying(50),
    tel_movil character varying(10),
    email character varying(50),
    fax character varying(15),
    observacion character varying(100),
    tel_fijo character varying(10),
    sexo smallint,
    idciudad character varying(10),
    idzona integer,
    saldo_nit_cc character varying(15),
    cupo_tope numeric(12,2) DEFAULT 0,
    dia_limites_facturas character varying(10)
);


ALTER TABLE clientes OWNER TO postgres;

--
-- Name: departamento; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE departamento (
    iddepartamento character varying(2) NOT NULL,
    nombre character varying(100)
);


ALTER TABLE departamento OWNER TO postgres;

--
-- Name: descuentos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE descuentos (
    iddescuentos numeric(4,2) NOT NULL,
    descpcion_descuento character varying(10)
);


ALTER TABLE descuentos OWNER TO postgres;

--
-- Name: detalle_factura_compra; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE detalle_factura_compra (
    iddetalle integer NOT NULL,
    cantidad numeric(12,2),
    idproducto character varying(15),
    idfactura_compra integer NOT NULL,
    precio_costo numeric(12,2),
    garantia character varying
);


ALTER TABLE detalle_factura_compra OWNER TO postgres;

--
-- Name: TABLE detalle_factura_compra; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE detalle_factura_compra IS 'ESTA TABLA REALIZA UN INSERCION EN LA TABLA PRODUCTO ACTULIZANDO EL STOCK+';


--
-- Name: detalle_factura_compra_iddetalle_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_compra_iddetalle_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_compra_iddetalle_seq OWNER TO postgres;

--
-- Name: detalle_factura_compra_iddetalle_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_compra_iddetalle_seq OWNED BY detalle_factura_compra.iddetalle;


--
-- Name: detalle_factura_compra_idfactura_compra_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_compra_idfactura_compra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_compra_idfactura_compra_seq OWNER TO postgres;

--
-- Name: detalle_factura_compra_idfactura_compra_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_compra_idfactura_compra_seq OWNED BY detalle_factura_compra.idfactura_compra;


--
-- Name: detalle_factura_compra_idproducto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_compra_idproducto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_compra_idproducto_seq OWNER TO postgres;

--
-- Name: detalle_factura_compra_idproducto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_compra_idproducto_seq OWNED BY detalle_factura_compra.idproducto;


--
-- Name: detalle_factura_venta; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE detalle_factura_venta (
    iddetalle integer NOT NULL,
    cantidad numeric(12,2),
    precio_venta_unitario numeric(12,2),
    valor_parcial numeric(12,2),
    idproducto character varying(15),
    idventa integer NOT NULL,
    descuento numeric(4,2) DEFAULT 0,
    iva numeric(4,2),
    aplicar_descuento smallint,
    utilidad_para_vendedor numeric(12,0)
);


ALTER TABLE detalle_factura_venta OWNER TO postgres;

--
-- Name: TABLE detalle_factura_venta; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE detalle_factura_venta IS 'ESTA TABLA REALIZA UN ACTUALIZACION EN LA TABLA PRODUCTO ACTULIZANDO EL STOCK-';


--
-- Name: detalle_factura_venta_iddetalle_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_venta_iddetalle_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_venta_iddetalle_seq OWNER TO postgres;

--
-- Name: detalle_factura_venta_iddetalle_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_venta_iddetalle_seq OWNED BY detalle_factura_venta.iddetalle;


--
-- Name: detalle_factura_venta_idproducto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_venta_idproducto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_venta_idproducto_seq OWNER TO postgres;

--
-- Name: detalle_factura_venta_idproducto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_venta_idproducto_seq OWNED BY detalle_factura_venta.idproducto;


--
-- Name: detalle_factura_venta_idventa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE detalle_factura_venta_idventa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE detalle_factura_venta_idventa_seq OWNER TO postgres;

--
-- Name: detalle_factura_venta_idventa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE detalle_factura_venta_idventa_seq OWNED BY detalle_factura_venta.idventa;


--
-- Name: deudas_memo; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE deudas_memo (
    id_deuda integer NOT NULL,
    nit_ccc character varying(15),
    valor numeric(15,2),
    descricion character varying(50),
    idventa integer,
    fecha date
);


ALTER TABLE deudas_memo OWNER TO postgres;

--
-- Name: TABLE deudas_memo; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE deudas_memo IS 'Tabla Abono Clientes
';


--
-- Name: COLUMN deudas_memo.idventa; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN deudas_memo.idventa IS 'PK para obtener el N factura';


--
-- Name: deudas_memo_id_deuda_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE deudas_memo_id_deuda_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE deudas_memo_id_deuda_seq OWNER TO postgres;

--
-- Name: deudas_memo_id_deuda_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE deudas_memo_id_deuda_seq OWNED BY deudas_memo.id_deuda;


--
-- Name: idventa; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE idventa
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE idventa OWNER TO postgres;

--
-- Name: n_factura; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE n_factura
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE n_factura OWNER TO postgres;

--
-- Name: facturar_ventas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE facturar_ventas (
    clientes_nit_cc character varying(15),
    idventa integer DEFAULT nextval('idventa'::regclass) NOT NULL,
    fecha_hora timestamp with time zone,
    tipo_de_venta smallint,
    abono numeric(12,2) DEFAULT 0,
    valor_total_pagar numeric(12,2) DEFAULT 0,
    n_factura character varying(15) DEFAULT nextval('n_factura'::regclass),
    iva_total numeric(12,2) DEFAULT 0,
    descuento_total numeric(12,2) DEFAULT 0,
    saldo numeric(12,2) DEFAULT 0,
    sub_total numeric(12,2) DEFAULT 0,
    estado smallint,
    user_id integer,
    printt smallint,
    vendedor character varying(50),
    utilidad_en_venta numeric(12,2) DEFAULT 0,
    forma_pago smallint
);


ALTER TABLE facturar_ventas OWNER TO postgres;

--
-- Name: TABLE facturar_ventas; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE facturar_ventas IS 'TABLA NO DEFINIDAD HASTA EL MOMENTO';


--
-- Name: COLUMN facturar_ventas.estado; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN facturar_ventas.estado IS '
';


--
-- Name: factura_abono; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW factura_abono AS
 SELECT clientes.nit_cc,
    facturar_ventas.idventa,
    (((((to_char(facturar_ventas.fecha_hora, 'yyyy-Mon-DD'::text) || ' '::text) || 'Factura N°'::text) || (facturar_ventas.n_factura)::text) || ', Saldo: '::text) || to_char(facturar_ventas.saldo, 'LFM9,999,999.99'::text)) AS factura_saldo
   FROM clientes,
    facturar_ventas
  WHERE (((facturar_ventas.clientes_nit_cc)::text = (clientes.nit_cc)::text) AND (facturar_ventas.saldo <> (0)::numeric));


ALTER TABLE factura_abono OWNER TO postgres;

--
-- Name: factura_detalle_vi; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW factura_detalle_vi AS
 SELECT facturar_ventas.idventa,
    detalle_factura_venta.descuento,
    detalle_factura_venta.idproducto,
    detalle_factura_venta.cantidad,
    detalle_factura_venta.precio_venta_unitario,
    detalle_factura_venta.valor_parcial,
    detalle_factura_venta.iva
   FROM facturar_ventas,
    detalle_factura_venta
  WHERE (facturar_ventas.idventa = detalle_factura_venta.idventa);


ALTER TABLE factura_detalle_vi OWNER TO postgres;

--
-- Name: phpgen_users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE phpgen_users (
    user_id integer NOT NULL,
    user_name character varying(500),
    user_password character varying(128)
);


ALTER TABLE phpgen_users OWNER TO postgres;

--
-- Name: factura_venta_vi; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW factura_venta_vi AS
 SELECT phpgen_users.user_id,
    facturar_ventas.idventa,
    facturar_ventas.n_factura,
    facturar_ventas.valor_total_pagar,
    facturar_ventas.abono,
    facturar_ventas.tipo_de_venta,
    facturar_ventas.fecha_hora,
    facturar_ventas.estado,
    facturar_ventas.saldo,
    facturar_ventas.sub_total
   FROM facturar_ventas,
    phpgen_users
  WHERE (phpgen_users.user_id = facturar_ventas.user_id);


ALTER TABLE factura_venta_vi OWNER TO postgres;

--
-- Name: facturar_ventas_idventa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE facturar_ventas_idventa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE facturar_ventas_idventa_seq OWNER TO postgres;

--
-- Name: facturar_ventas_idventa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE facturar_ventas_idventa_seq OWNED BY facturar_ventas.idventa;


--
-- Name: importacion_excel; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE importacion_excel (
    id_importacion integer NOT NULL,
    url_archivo character varying
);


ALTER TABLE importacion_excel OWNER TO postgres;

--
-- Name: importacion_excel_id_importacion_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE importacion_excel_id_importacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE importacion_excel_id_importacion_seq OWNER TO postgres;

--
-- Name: importacion_excel_id_importacion_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE importacion_excel_id_importacion_seq OWNED BY importacion_excel.id_importacion;


--
-- Name: iva; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iva (
    id_iva numeric(4,2) NOT NULL,
    descripcion character varying(5)
);


ALTER TABLE iva OWNER TO postgres;

--
-- Name: phpgen_user_perms; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE phpgen_user_perms (
    user_id integer NOT NULL,
    page_name character varying(255) NOT NULL,
    perm_name character varying(6) NOT NULL
);


ALTER TABLE phpgen_user_perms OWNER TO postgres;

--
-- Name: productos_idcaracter_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE productos_idcaracter_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE productos_idcaracter_seq OWNER TO postgres;

--
-- Name: productos_idcaracter_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE productos_idcaracter_seq OWNED BY productos.idcaracter;


--
-- Name: productos_idfactura_compra_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE productos_idfactura_compra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE productos_idfactura_compra_seq OWNER TO postgres;

--
-- Name: productos_idfactura_compra_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE productos_idfactura_compra_seq OWNED BY productos.idfactura_compra;


--
-- Name: productos_idproducto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE productos_idproducto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE productos_idproducto_seq OWNER TO postgres;

--
-- Name: productos_idproducto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE productos_idproducto_seq OWNED BY productos.idproducto;


--
-- Name: productos_idtipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE productos_idtipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE productos_idtipo_seq OWNER TO postgres;

--
-- Name: productos_idtipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE productos_idtipo_seq OWNED BY productos.idtipo;


--
-- Name: productos_idunidad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE productos_idunidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE productos_idunidad_seq OWNER TO postgres;

--
-- Name: productos_idunidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE productos_idunidad_seq OWNED BY productos.idunidad;


--
-- Name: proveedor; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE proveedor (
    nit_cc character varying(15) NOT NULL,
    nombre_comercial character varying(70),
    n_cuenta character varying(20),
    direccion character varying(40),
    tel_movil character varying(10),
    email character varying(50),
    fax character varying(15),
    observacion character varying(100),
    tel_fijo character varying(10),
    idciudad character varying(10) NOT NULL
);


ALTER TABLE proveedor OWNER TO postgres;

--
-- Name: registrar_compra_idfactura_compra_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE registrar_compra_idfactura_compra_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE registrar_compra_idfactura_compra_seq OWNER TO postgres;

--
-- Name: registrar_compra_idfactura_compra_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE registrar_compra_idfactura_compra_seq OWNED BY registrar_compra.idfactura_compra;


--
-- Name: zona; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE zona (
    idzona integer NOT NULL,
    descripcion character varying,
    saldo_zona integer
);


ALTER TABLE zona OWNER TO postgres;

--
-- Name: saldo_zona_v; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW saldo_zona_v AS
 SELECT zona.idzona,
    sum(facturar_ventas.saldo) AS saldo
   FROM facturar_ventas,
    clientes,
    zona
  WHERE (((clientes.nit_cc)::text = (facturar_ventas.clientes_nit_cc)::text) AND (zona.idzona = clientes.idzona))
  GROUP BY zona.idzona;


ALTER TABLE saldo_zona_v OWNER TO postgres;

--
-- Name: tipo_productos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tipo_productos (
    idtipo integer NOT NULL,
    descripcion character varying(30)
);


ALTER TABLE tipo_productos OWNER TO postgres;

--
-- Name: tipo_productos_idtipo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tipo_productos_idtipo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE tipo_productos_idtipo_seq OWNER TO postgres;

--
-- Name: tipo_productos_idtipo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tipo_productos_idtipo_seq OWNED BY tipo_productos.idtipo;


--
-- Name: unidades; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE unidades (
    idunidad integer NOT NULL,
    descripcion character varying(15)
);


ALTER TABLE unidades OWNER TO postgres;

--
-- Name: unidades_idunidad_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE unidades_idunidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE unidades_idunidad_seq OWNER TO postgres;

--
-- Name: unidades_idunidad_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE unidades_idunidad_seq OWNED BY unidades.idunidad;


--
-- Name: utilidad_factura_vendedor_vi; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW utilidad_factura_vendedor_vi AS
 SELECT sum(detalle_factura_venta.utilidad_para_vendedor) AS utilidad,
    detalle_factura_venta.idventa
   FROM detalle_factura_venta
  GROUP BY detalle_factura_venta.idventa;


ALTER TABLE utilidad_factura_vendedor_vi OWNER TO postgres;

--
-- Name: vendedor_vi; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vendedor_vi AS
 SELECT phpgen_users.user_id,
    phpgen_users.user_name
   FROM phpgen_users;


ALTER TABLE vendedor_vi OWNER TO postgres;

--
-- Name: zona_idzona_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE zona_idzona_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE zona_idzona_seq OWNER TO postgres;

--
-- Name: zona_idzona_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE zona_idzona_seq OWNED BY zona.idzona;


--
-- Name: idcaracter; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY caracteristicas ALTER COLUMN idcaracter SET DEFAULT nextval('caracteristicas_idcaracter_seq'::regclass);


--
-- Name: iddetalle; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_compra ALTER COLUMN iddetalle SET DEFAULT nextval('detalle_factura_compra_iddetalle_seq'::regclass);


--
-- Name: idproducto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_compra ALTER COLUMN idproducto SET DEFAULT nextval('detalle_factura_compra_idproducto_seq'::regclass);


--
-- Name: idfactura_compra; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_compra ALTER COLUMN idfactura_compra SET DEFAULT nextval('detalle_factura_compra_idfactura_compra_seq'::regclass);


--
-- Name: iddetalle; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_venta ALTER COLUMN iddetalle SET DEFAULT nextval('detalle_factura_venta_iddetalle_seq'::regclass);


--
-- Name: id_deuda; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY deudas_memo ALTER COLUMN id_deuda SET DEFAULT nextval('deudas_memo_id_deuda_seq'::regclass);


--
-- Name: id_importacion; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY importacion_excel ALTER COLUMN id_importacion SET DEFAULT nextval('importacion_excel_id_importacion_seq'::regclass);


--
-- Name: idproducto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos ALTER COLUMN idproducto SET DEFAULT nextval('productos_idproducto_seq'::regclass);


--
-- Name: idfactura_compra; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY registrar_compra ALTER COLUMN idfactura_compra SET DEFAULT nextval('registrar_compra_idfactura_compra_seq'::regclass);


--
-- Name: idtipo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tipo_productos ALTER COLUMN idtipo SET DEFAULT nextval('tipo_productos_idtipo_seq'::regclass);


--
-- Name: idunidad; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY unidades ALTER COLUMN idunidad SET DEFAULT nextval('unidades_idunidad_seq'::regclass);


--
-- Name: idzona; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY zona ALTER COLUMN idzona SET DEFAULT nextval('zona_idzona_seq'::regclass);


--
-- Data for Name: caracteristicas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO caracteristicas (idcaracter, marca, modelo) VALUES (83, 'LIGE', NULL);
INSERT INTO caracteristicas (idcaracter, marca, modelo) VALUES (84, 'BIDEN', NULL);
INSERT INTO caracteristicas (idcaracter, marca, modelo) VALUES (85, 'MALLOON', NULL);
INSERT INTO caracteristicas (idcaracter, marca, modelo) VALUES (86, 'YAZOLE', NULL);
INSERT INTO caracteristicas (idcaracter, marca, modelo) VALUES (87, 'SINOBI', NULL);


--
-- Name: caracteristicas_idcaracter_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('caracteristicas_idcaracter_seq', 87, true);


--
-- Data for Name: ciudad; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('﻿1', 'MEDELLIN', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('2', 'ABEJORRAL', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('3', 'ABRIAQUI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('4', 'ALEJANDRIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('5', 'AMAGA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('6', 'AMALFI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('7', 'ANDES', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('8', 'ANGELOPOLIS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('9', 'ANGOSTURA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('10', 'ANORI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('11', 'SANTAFE DE ANTIOQUIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('12', 'ANZA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('13', 'APARTADO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('14', 'ARBOLETES', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('15', 'ARGELIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('16', 'ARMENIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('17', 'BARBOSA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('18', 'BELMIRA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('19', 'BELLO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('20', 'BETANIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('21', 'BETULIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('22', 'CIUDAD BOLIVAR', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('23', 'BRICEÑO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('24', 'BURITICA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('25', 'CACERES', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('26', 'CAICEDO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('27', 'CALDAS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('28', 'CAMPAMENTO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('29', 'CAÑASGORDAS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('30', 'CARACOLI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('31', 'CARAMANTA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('32', 'CAREPA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('33', 'EL CARMEN DE VIBORAL', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('34', 'CAROLINA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('35', 'CAUCASIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('36', 'CHIGORODO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('37', 'CISNEROS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('38', 'COCORNA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('39', 'CONCEPCION', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('40', 'CONCORDIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('41', 'COPACABANA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('42', 'DABEIBA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('43', 'DON MATIAS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('44', 'EBEJICO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('45', 'EL BAGRE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('46', 'ENTRERRIOS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('47', 'ENVIGADO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('48', 'FREDONIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('49', 'FRONTINO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('50', 'GIRALDO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('51', 'GIRARDOTA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('52', 'GOMEZ PLATA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('53', 'GRANADA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('54', 'GUADALUPE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('55', 'GUARNE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('56', 'GUATAPE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('57', 'HELICONIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('58', 'HISPANIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('59', 'ITAGUI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('60', 'ITUANGO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('61', 'JARDIN', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('62', 'JERICO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('63', 'LA CEJA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('64', 'LA ESTRELLA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('65', 'LA PINTADA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('66', 'LA UNION', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('67', 'LIBORINA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('68', 'MACEO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('69', 'MARINILLA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('70', 'MONTEBELLO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('71', 'MURINDO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('72', 'MUTATA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('73', 'NARIÑO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('74', 'NECOCLI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('75', 'NECHI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('76', 'OLAYA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('77', 'PEÐOL', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('78', 'PEQUE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('79', 'PUEBLORRICO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('80', 'PUERTO BERRIO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('81', 'PUERTO NARE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('82', 'PUERTO TRIUNFO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('83', 'REMEDIOS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('84', 'RETIRO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('85', 'RIONEGRO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('86', 'SABANALARGA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('87', 'SABANETA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('88', 'SALGAR', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('89', 'SAN ANDRES DE CUERQUIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('90', 'SAN CARLOS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('91', 'SAN FRANCISCO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('92', 'SAN JERONIMO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('93', 'SAN JOSE DE LA MONTAÑA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('94', 'SAN JUAN DE URABA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('95', 'SAN LUIS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('96', 'SAN PEDRO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('97', 'SAN PEDRO DE URABA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('98', 'SAN RAFAEL', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('99', 'SAN ROQUE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('100', 'SAN VICENTE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('101', 'SANTA BARBARA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('102', 'SANTA ROSA DE OSOS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('103', 'SANTO DOMINGO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('104', 'EL SANTUARIO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('105', 'SEGOVIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('106', 'SONSON', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('107', 'SOPETRAN', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('108', 'TAMESIS', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('109', 'TARAZA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('110', 'TARSO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('111', 'TITIRIBI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('112', 'TOLEDO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('113', 'TURBO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('114', 'URAMITA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('115', 'URRAO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('116', 'VALDIVIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('117', 'VALPARAISO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('118', 'VEGACHI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('119', 'VENECIA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('120', 'VIGIA DEL FUERTE', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('121', 'YALI', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('122', 'YARUMAL', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('123', 'YOLOMBO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('124', 'YONDO', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('125', 'ZARAGOZA', '05');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('126', 'BARRANQUILLA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('127', 'BARANOA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('128', 'CAMPO DE LA CRUZ', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('129', 'CANDELARIA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('130', 'GALAPA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('131', 'JUAN DE ACOSTA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('132', 'LURUACO', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('133', 'MALAMBO', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('134', 'MANATI', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('135', 'PALMAR DE VARELA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('136', 'PIOJO', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('137', 'POLONUEVO', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('138', 'PONEDERA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('139', 'PUERTO COLOMBIA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('140', 'REPELON', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('141', 'SABANAGRANDE', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('142', 'SABANALARGA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('143', 'SANTA LUCIA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('144', 'SANTO TOMAS', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('145', 'SOLEDAD', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('146', 'SUAN', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('147', 'TUBARA', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('148', 'USIACURI', '08');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('149', 'BOGOTA, D.C.', '11');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('150', 'CARTAGENA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('151', 'ACHI', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('152', 'ALTOS DEL ROSARIO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('153', 'ARENAL', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('154', 'ARJONA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('155', 'ARROYOHONDO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('156', 'BARRANCO DE LOBA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('157', 'CALAMAR', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('158', 'CANTAGALLO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('159', 'CICUCO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('160', 'CORDOBA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('161', 'CLEMENCIA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('162', 'EL CARMEN DE BOLIVAR', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('163', 'EL GUAMO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('164', 'EL PEÑON', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('165', 'HATILLO DE LOBA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('166', 'MAGANGUE', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('167', 'MAHATES', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('168', 'MARGARITA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('169', 'MARIA LA BAJA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('170', 'MONTECRISTO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('171', 'MOMPOS', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('172', 'NOROSI', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('173', 'MORALES', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('174', 'PINILLOS', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('175', 'REGIDOR', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('176', 'RIO VIEJO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('177', 'SAN CRISTOBAL', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('178', 'SAN ESTANISLAO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('179', 'SAN FERNANDO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('180', 'SAN JACINTO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('181', 'SAN JACINTO DEL CAUCA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('182', 'SAN JUAN NEPOMUCENO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('183', 'SAN MARTIN DE LOBA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('184', 'SAN PABLO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('185', 'SANTA CATALINA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('186', 'SANTA ROSA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('187', 'SANTA ROSA DEL SUR', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('188', 'SIMITI', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('189', 'SOPLAVIENTO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('190', 'TALAIGUA NUEVO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('191', 'TIQUISIO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('192', 'TURBACO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('193', 'TURBANA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('194', 'VILLANUEVA', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('195', 'ZAMBRANO', '13');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('196', 'TUNJA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('197', 'ALMEIDA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('198', 'AQUITANIA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('199', 'ARCABUCO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('200', 'BELEN', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('201', 'BERBEO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('202', 'BETEITIVA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('203', 'BOAVITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('204', 'BOYACA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('205', 'BRICEÑO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('206', 'BUENAVISTA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('207', 'BUSBANZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('208', 'CALDAS', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('209', 'CAMPOHERMOSO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('210', 'CERINZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('211', 'CHINAVITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('212', 'CHIQUINQUIRA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('213', 'CHISCAS', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('214', 'CHITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('215', 'CHITARAQUE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('216', 'CHIVATA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('217', 'CIENEGA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('218', 'COMBITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('219', 'COPER', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('220', 'CORRALES', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('221', 'COVARACHIA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('222', 'CUBARA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('223', 'CUCAITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('224', 'CUITIVA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('225', 'CHIQUIZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('226', 'CHIVOR', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('227', 'DUITAMA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('228', 'EL COCUY', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('229', 'EL ESPINO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('230', 'FIRAVITOBA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('231', 'FLORESTA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('232', 'GACHANTIVA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('233', 'GAMEZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('234', 'GARAGOA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('235', 'GUACAMAYAS', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('236', 'GUATEQUE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('237', 'GUAYATA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('238', 'GsICAN', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('239', 'IZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('240', 'JENESANO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('241', 'JERICO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('242', 'LABRANZAGRANDE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('243', 'LA CAPILLA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('244', 'LA VICTORIA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('245', 'LA UVITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('246', 'VILLA DE LEYVA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('247', 'MACANAL', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('248', 'MARIPI', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('249', 'MIRAFLORES', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('250', 'MONGUA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('251', 'MONGUI', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('252', 'MONIQUIRA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('253', 'MOTAVITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('254', 'MUZO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('255', 'NOBSA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('256', 'NUEVO COLON', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('257', 'OICATA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('258', 'OTANCHE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('259', 'PACHAVITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('260', 'PAEZ', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('261', 'PAIPA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('262', 'PAJARITO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('263', 'PANQUEBA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('264', 'PAUNA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('265', 'PAYA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('266', 'PAZ DE RIO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('267', 'PESCA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('268', 'PISBA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('269', 'PUERTO BOYACA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('270', 'QUIPAMA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('271', 'RAMIRIQUI', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('272', 'RAQUIRA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('273', 'RONDON', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('274', 'SABOYA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('275', 'SACHICA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('276', 'SAMACA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('277', 'SAN EDUARDO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('278', 'SAN JOSE DE PARE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('279', 'SAN LUIS DE GACENO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('280', 'SAN MATEO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('281', 'SAN MIGUEL DE SEMA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('282', 'SAN PABLO DE BORBUR', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('283', 'SANTANA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('284', 'SANTA MARIA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('285', 'SANTA ROSA DE VITERBO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('286', 'SANTA SOFIA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('287', 'SATIVANORTE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('288', 'SATIVASUR', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('289', 'SIACHOQUE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('290', 'SOATA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('291', 'SOCOTA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('292', 'SOCHA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('293', 'SOGAMOSO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('294', 'SOMONDOCO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('295', 'SORA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('296', 'SOTAQUIRA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('297', 'SORACA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('298', 'SUSACON', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('299', 'SUTAMARCHAN', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('300', 'SUTATENZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('301', 'TASCO', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('302', 'TENZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('303', 'TIBANA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('304', 'TIBASOSA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('305', 'TINJACA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('306', 'TIPACOQUE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('307', 'TOCA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('308', 'TOGsI', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('309', 'TOPAGA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('310', 'TOTA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('311', 'TUNUNGUA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('312', 'TURMEQUE', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('313', 'TUTA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('314', 'TUTAZA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('315', 'UMBITA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('316', 'VENTAQUEMADA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('317', 'VIRACACHA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('318', 'ZETAQUIRA', '15');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('319', 'MANIZALES', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('320', 'AGUADAS', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('321', 'ANSERMA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('322', 'ARANZAZU', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('323', 'BELALCAZAR', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('324', 'CHINCHINA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('325', 'FILADELFIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('326', 'LA DORADA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('327', 'LA MERCED', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('328', 'MANZANARES', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('329', 'MARMATO', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('330', 'MARQUETALIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('331', 'MARULANDA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('332', 'NEIRA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('333', 'NORCASIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('334', 'PACORA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('335', 'PALESTINA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('336', 'PENSILVANIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('337', 'RIOSUCIO', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('338', 'RISARALDA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('339', 'SALAMINA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('340', 'SAMANA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('341', 'SAN JOSE', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('342', 'SUPIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('343', 'VICTORIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('344', 'VILLAMARIA', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('345', 'VITERBO', '17');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('346', 'FLORENCIA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('347', 'ALBANIA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('348', 'BELEN DE LOS ANDAQUIES', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('349', 'CARTAGENA DEL CHAIRA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('350', 'CURILLO', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('351', 'EL DONCELLO', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('352', 'EL PAUJIL', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('353', 'LA MONTAÑITA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('354', 'MILAN', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('355', 'MORELIA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('356', 'PUERTO RICO', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('357', 'SAN JOSE DEL FRAGUA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('358', 'SAN VICENTE DEL CAGUAN', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('359', 'SOLANO', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('360', 'SOLITA', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('361', 'VALPARAISO', '18');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('362', 'POPAYAN', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('363', 'ALMAGUER', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('364', 'ARGELIA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('365', 'BALBOA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('366', 'BOLIVAR', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('367', 'BUENOS AIRES', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('368', 'CAJIBIO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('369', 'CALDONO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('370', 'CALOTO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('371', 'CORINTO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('372', 'EL TAMBO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('373', 'FLORENCIA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('374', 'GUACHENE', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('375', 'GUAPI', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('376', 'INZA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('377', 'JAMBALO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('378', 'LA SIERRA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('379', 'LA VEGA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('380', 'LOPEZ', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('381', 'MERCADERES', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('382', 'MIRANDA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('383', 'MORALES', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('384', 'PADILLA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('385', 'PAEZ', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('386', 'PATIA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('387', 'PIAMONTE', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('388', 'PIENDAMO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('389', 'PUERTO TEJADA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('390', 'PURACE', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('391', 'ROSAS', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('392', 'SAN SEBASTIAN', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('393', 'SANTANDER DE QUILICHAO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('394', 'SANTA ROSA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('395', 'SILVIA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('396', 'SOTARA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('397', 'SUAREZ', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('398', 'SUCRE', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('399', 'TIMBIO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('400', 'TIMBIQUI', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('401', 'TORIBIO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('402', 'TOTORO', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('403', 'VILLA RICA', '19');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('404', 'VALLEDUPAR', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('405', 'AGUACHICA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('406', 'AGUSTIN CODAZZI', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('407', 'ASTREA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('408', 'BECERRIL', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('409', 'BOSCONIA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('410', 'CHIMICHAGUA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('411', 'CHIRIGUANA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('412', 'CURUMANI', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('413', 'EL COPEY', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('414', 'EL PASO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('415', 'GAMARRA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('416', 'GONZALEZ', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('417', 'LA GLORIA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('418', 'LA JAGUA DE IBIRICO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('419', 'MANAURE', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('420', 'PAILITAS', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('421', 'PELAYA', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('422', 'PUEBLO BELLO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('423', 'RIO DE ORO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('424', 'LA PAZ', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('425', 'SAN ALBERTO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('426', 'SAN DIEGO', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('427', 'SAN MARTIN', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('428', 'TAMALAMEQUE', '20');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('429', 'MONTERIA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('430', 'AYAPEL', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('431', 'BUENAVISTA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('432', 'CANALETE', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('433', 'CERETE', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('434', 'CHIMA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('435', 'CHINU', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('436', 'CIENAGA DE ORO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('437', 'COTORRA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('438', 'LA APARTADA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('439', 'LORICA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('440', 'LOS CORDOBAS', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('441', 'MOMIL', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('442', 'MONTELIBANO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('443', 'MOÑITOS', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('444', 'PLANETA RICA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('445', 'PUEBLO NUEVO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('446', 'PUERTO ESCONDIDO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('447', 'PUERTO LIBERTADOR', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('448', 'PURISIMA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('449', 'SAHAGUN', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('450', 'SAN ANDRES SOTAVENTO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('451', 'SAN ANTERO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('452', 'SAN BERNARDO DEL VIENTO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('453', 'SAN CARLOS', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('454', 'SAN PELAYO', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('455', 'TIERRALTA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('456', 'VALENCIA', '23');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('457', 'AGUA DE DIOS', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('458', 'ALBAN', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('459', 'ANAPOIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('460', 'ANOLAIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('461', 'ARBELAEZ', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('462', 'BELTRAN', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('463', 'BITUIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('464', 'BOJACA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('465', 'CABRERA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('466', 'CACHIPAY', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('467', 'CAJICA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('468', 'CAPARRAPI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('469', 'CAQUEZA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('470', 'CARMEN DE CARUPA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('471', 'CHAGUANI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('472', 'CHIA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('473', 'CHIPAQUE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('474', 'CHOACHI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('475', 'CHOCONTA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('476', 'COGUA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('477', 'COTA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('478', 'CUCUNUBA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('479', 'EL COLEGIO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('480', 'EL PEÑON', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('481', 'EL ROSAL', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('482', 'FACATATIVA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('483', 'FOMEQUE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('484', 'FOSCA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('485', 'FUNZA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('486', 'FUQUENE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('487', 'FUSAGASUGA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('488', 'GACHALA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('489', 'GACHANCIPA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('490', 'GACHETA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('491', 'GAMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('492', 'GIRARDOT', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('493', 'GRANADA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('494', 'GUACHETA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('495', 'GUADUAS', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('496', 'GUASCA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('497', 'GUATAQUI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('498', 'GUATAVITA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('499', 'GUAYABAL DE SIQUIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('500', 'GUAYABETAL', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('501', 'GUTIERREZ', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('502', 'JERUSALEN', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('503', 'JUNIN', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('504', 'LA CALERA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('505', 'LA MESA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('506', 'LA PALMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('507', 'LA PEÑA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('508', 'LA VEGA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('509', 'LENGUAZAQUE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('510', 'MACHETA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('511', 'MADRID', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('512', 'MANTA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('513', 'MEDINA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('514', 'MOSQUERA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('515', 'NARIÑO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('516', 'NEMOCON', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('517', 'NILO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('518', 'NIMAIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('519', 'NOCAIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('520', 'VENECIA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('521', 'PACHO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('522', 'PAIME', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('523', 'PANDI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('524', 'PARATEBUENO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('525', 'PASCA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('526', 'PUERTO SALGAR', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('527', 'PULI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('528', 'QUEBRADANEGRA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('529', 'QUETAME', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('530', 'QUIPILE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('531', 'APULO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('532', 'RICAURTE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('533', 'SAN ANTONIO DEL TEQUENDAMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('534', 'SAN BERNARDO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('535', 'SAN CAYETANO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('536', 'SAN FRANCISCO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('537', 'SAN JUAN DE RIO SECO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('538', 'SASAIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('539', 'SESQUILE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('540', 'SIBATE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('541', 'SILVANIA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('542', 'SIMIJACA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('543', 'SOACHA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('544', 'SOPO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('545', 'SUBACHOQUE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('546', 'SUESCA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('547', 'SUPATA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('548', 'SUSA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('549', 'SUTATAUSA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('550', 'TABIO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('551', 'TAUSA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('552', 'TENA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('553', 'TENJO', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('554', 'TIBACUY', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('555', 'TIBIRITA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('556', 'TOCAIMA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('557', 'TOCANCIPA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('558', 'TOPAIPI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('559', 'UBALA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('560', 'UBAQUE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('561', 'VILLA DE SAN DIEGO DE UBATE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('562', 'UNE', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('563', 'UTICA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('564', 'VERGARA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('565', 'VIANI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('566', 'VILLAGOMEZ', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('567', 'VILLAPINZON', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('568', 'VILLETA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('569', 'VIOTA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('570', 'YACOPI', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('571', 'ZIPACON', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('572', 'ZIPAQUIRA', '25');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('573', 'QUIBDO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('574', 'ACANDI', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('575', 'ALTO BAUDO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('576', 'ATRATO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('577', 'BAGADO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('578', 'BAHIA SOLANO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('579', 'BAJO BAUDO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('580', 'BOJAYA', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('581', 'EL CANTON DEL SAN PABLO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('582', 'CARMEN DEL DARIEN', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('583', 'CERTEGUI', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('584', 'CONDOTO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('585', 'EL CARMEN DE ATRATO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('586', 'EL LITORAL DEL SAN JUAN', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('587', 'ISTMINA', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('588', 'JURADO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('589', 'LLORO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('590', 'MEDIO ATRATO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('591', 'MEDIO BAUDO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('592', 'MEDIO SAN JUAN', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('593', 'NOVITA', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('594', 'NUQUI', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('595', 'RIO IRO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('596', 'RIO QUITO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('597', 'RIOSUCIO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('598', 'SAN JOSE DEL PALMAR', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('599', 'SIPI', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('600', 'TADO', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('601', 'UNGUIA', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('602', 'UNION PANAMERICANA', '27');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('603', 'NEIVA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('604', 'ACEVEDO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('605', 'AGRADO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('606', 'AIPE', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('607', 'ALGECIRAS', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('608', 'ALTAMIRA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('609', 'BARAYA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('610', 'CAMPOALEGRE', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('611', 'COLOMBIA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('612', 'ELIAS', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('613', 'GARZON', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('614', 'GIGANTE', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('615', 'GUADALUPE', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('616', 'HOBO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('617', 'IQUIRA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('618', 'ISNOS', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('619', 'LA ARGENTINA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('620', 'LA PLATA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('621', 'NATAGA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('622', 'OPORAPA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('623', 'PAICOL', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('624', 'PALERMO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('625', 'PALESTINA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('626', 'PITAL', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('627', 'PITALITO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('628', 'RIVERA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('629', 'SALADOBLANCO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('630', 'SAN AGUSTIN', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('631', 'SANTA MARIA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('632', 'SUAZA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('633', 'TARQUI', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('634', 'TESALIA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('635', 'TELLO', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('636', 'TERUEL', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('637', 'TIMANA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('638', 'VILLAVIEJA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('639', 'YAGUARA', '41');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('640', 'RIOHACHA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('641', 'ALBANIA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('642', 'BARRANCAS', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('643', 'DIBULLA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('644', 'DISTRACCION', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('645', 'EL MOLINO', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('646', 'FONSECA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('647', 'HATONUEVO', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('648', 'LA JAGUA DEL PILAR', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('649', 'MAICAO', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('650', 'MANAURE', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('651', 'SAN JUAN DEL CESAR', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('652', 'URIBIA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('653', 'URUMITA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('654', 'VILLANUEVA', '44');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('655', 'SANTA MARTA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('656', 'ALGARROBO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('657', 'ARACATACA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('658', 'ARIGUANI', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('659', 'CERRO SAN ANTONIO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('660', 'CHIBOLO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('661', 'CIENAGA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('662', 'CONCORDIA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('663', 'EL BANCO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('664', 'EL PIÑON', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('665', 'EL RETEN', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('666', 'FUNDACION', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('667', 'GUAMAL', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('668', 'NUEVA GRANADA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('669', 'PEDRAZA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('670', 'PIJIÑO DEL CARMEN', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('671', 'PIVIJAY', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('672', 'PLATO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('673', 'PUEBLOVIEJO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('674', 'REMOLINO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('675', 'SABANAS DE SAN ANGEL', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('676', 'SALAMINA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('677', 'SAN SEBASTIAN DE BUENAVISTA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('678', 'SAN ZENON', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('679', 'SANTA ANA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('680', 'SANTA BARBARA DE PINTO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('681', 'SITIONUEVO', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('682', 'TENERIFE', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('683', 'ZAPAYAN', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('684', 'ZONA BANANERA', '47');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('685', 'VILLAVICENCIO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('686', 'ACACIAS', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('687', 'BARRANCA DE UPIA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('688', 'CABUYARO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('689', 'CASTILLA LA NUEVA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('690', 'CUBARRAL', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('691', 'CUMARAL', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('692', 'EL CALVARIO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('693', 'EL CASTILLO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('694', 'EL DORADO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('695', 'FUENTE DE ORO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('696', 'GRANADA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('697', 'GUAMAL', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('698', 'MAPIRIPAN', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('699', 'MESETAS', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('700', 'LA MACARENA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('701', 'URIBE', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('702', 'LEJANIAS', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('703', 'PUERTO CONCORDIA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('704', 'PUERTO GAITAN', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('705', 'PUERTO LOPEZ', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('706', 'PUERTO LLERAS', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('707', 'PUERTO RICO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('708', 'RESTREPO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('709', 'SAN CARLOS DE GUAROA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('710', 'SAN JUAN DE ARAMA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('711', 'SAN JUANITO', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('712', 'SAN MARTIN', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('713', 'VISTAHERMOSA', '50');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('714', 'PASTO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('715', 'ALBAN', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('716', 'ALDANA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('717', 'ANCUYA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('718', 'ARBOLEDA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('719', 'BARBACOAS', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('720', 'BELEN', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('721', 'BUESACO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('722', 'COLON', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('723', 'CONSACA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('724', 'CONTADERO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('725', 'CORDOBA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('726', 'CUASPUD', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('727', 'CUMBAL', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('728', 'CUMBITARA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('729', 'CHACHAGsI', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('730', 'EL CHARCO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('731', 'EL PEÑOL', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('732', 'EL ROSARIO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('733', 'EL TABLON DE GOMEZ', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('734', 'EL TAMBO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('735', 'FUNES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('736', 'GUACHUCAL', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('737', 'GUAITARILLA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('738', 'GUALMATAN', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('739', 'ILES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('740', 'IMUES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('741', 'IPIALES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('742', 'LA CRUZ', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('743', 'LA FLORIDA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('744', 'LA LLANADA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('745', 'LA TOLA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('746', 'LA UNION', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('747', 'LEIVA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('748', 'LINARES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('749', 'LOS ANDES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('750', 'MAGsI', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('751', 'MALLAMA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('752', 'MOSQUERA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('753', 'NARIÑO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('754', 'OLAYA HERRERA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('755', 'OSPINA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('756', 'FRANCISCO PIZARRO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('757', 'POLICARPA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('758', 'POTOSI', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('759', 'PROVIDENCIA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('760', 'PUERRES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('761', 'PUPIALES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('762', 'RICAURTE', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('763', 'ROBERTO PAYAN', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('764', 'SAMANIEGO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('765', 'SANDONA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('766', 'SAN BERNARDO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('767', 'SAN LORENZO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('768', 'SAN PABLO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('769', 'SAN PEDRO DE CARTAGO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('770', 'SANTA BARBARA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('771', 'SANTACRUZ', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('772', 'SAPUYES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('773', 'TAMINANGO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('774', 'TANGUA', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('775', 'SAN ANDRES DE TUMACO', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('776', 'TUQUERRES', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('777', 'YACUANQUER', '52');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('778', 'CUCUTA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('779', 'ABREGO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('780', 'ARBOLEDAS', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('781', 'BOCHALEMA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('782', 'BUCARASICA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('783', 'CACOTA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('784', 'CACHIRA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('785', 'CHINACOTA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('786', 'CHITAGA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('787', 'CONVENCION', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('788', 'CUCUTILLA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('789', 'DURANIA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('790', 'EL CARMEN', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('791', 'EL TARRA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('792', 'EL ZULIA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('793', 'GRAMALOTE', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('794', 'HACARI', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('795', 'HERRAN', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('796', 'LABATECA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('797', 'LA ESPERANZA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('798', 'LA PLAYA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('799', 'LOS PATIOS', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('800', 'LOURDES', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('801', 'MUTISCUA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('802', 'OCAÑA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('803', 'PAMPLONA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('804', 'PAMPLONITA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('805', 'PUERTO SANTANDER', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('806', 'RAGONVALIA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('807', 'SALAZAR', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('808', 'SAN CALIXTO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('809', 'SAN CAYETANO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('810', 'SANTIAGO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('811', 'SARDINATA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('812', 'SILOS', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('813', 'TEORAMA', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('814', 'TIBU', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('815', 'TOLEDO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('816', 'VILLA CARO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('817', 'VILLA DEL ROSARIO', '54');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('818', 'ARMENIA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('819', 'BUENAVISTA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('820', 'CALARCA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('821', 'CIRCASIA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('822', 'CORDOBA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('823', 'FILANDIA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('824', 'GENOVA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('825', 'LA TEBAIDA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('826', 'MONTENEGRO', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('827', 'PIJAO', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('828', 'QUIMBAYA', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('829', 'SALENTO', '63');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('830', 'PEREIRA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('831', 'APIA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('832', 'BALBOA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('833', 'BELEN DE UMBRIA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('834', 'DOSQUEBRADAS', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('835', 'GUATICA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('836', 'LA CELIA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('837', 'LA VIRGINIA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('838', 'MARSELLA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('839', 'MISTRATO', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('840', 'PUEBLO RICO', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('841', 'QUINCHIA', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('842', 'SANTA ROSA DE CABAL', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('843', 'SANTUARIO', '66');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('844', 'BUCARAMANGA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('845', 'AGUADA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('846', 'ALBANIA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('847', 'ARATOCA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('848', 'BARBOSA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('849', 'BARICHARA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('850', 'BARRANCABERMEJA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('851', 'BETULIA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('852', 'BOLIVAR', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('853', 'CABRERA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('854', 'CALIFORNIA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('855', 'CAPITANEJO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('856', 'CARCASI', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('857', 'CEPITA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('858', 'CERRITO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('859', 'CHARALA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('860', 'CHARTA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('861', 'CHIMA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('862', 'CHIPATA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('863', 'CIMITARRA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('864', 'CONCEPCION', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('865', 'CONFINES', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('866', 'CONTRATACION', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('867', 'COROMORO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('868', 'CURITI', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('869', 'EL CARMEN DE CHUCURI', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('870', 'EL GUACAMAYO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('871', 'EL PEÑON', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('872', 'EL PLAYON', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('873', 'ENCINO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('874', 'ENCISO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('875', 'FLORIAN', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('876', 'FLORIDABLANCA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('877', 'GALAN', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('878', 'GAMBITA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('879', 'GIRON', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('880', 'GUACA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('881', 'GUADALUPE', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('882', 'GUAPOTA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('883', 'GUAVATA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('884', 'GsEPSA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('885', 'HATO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('886', 'JESUS MARIA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('887', 'JORDAN', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('888', 'LA BELLEZA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('889', 'LANDAZURI', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('890', 'LA PAZ', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('891', 'LEBRIJA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('892', 'LOS SANTOS', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('893', 'MACARAVITA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('894', 'MALAGA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('895', 'MATANZA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('896', 'MOGOTES', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('897', 'MOLAGAVITA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('898', 'OCAMONTE', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('899', 'OIBA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('900', 'ONZAGA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('901', 'PALMAR', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('902', 'PALMAS DEL SOCORRO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('903', 'PARAMO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('904', 'PIEDECUESTA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('905', 'PINCHOTE', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('906', 'PUENTE NACIONAL', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('907', 'PUERTO PARRA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('908', 'PUERTO WILCHES', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('909', 'RIONEGRO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('910', 'SABANA DE TORRES', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('911', 'SAN ANDRES', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('912', 'SAN BENITO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('913', 'SAN GIL', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('914', 'SAN JOAQUIN', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('915', 'SAN JOSE DE MIRANDA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('916', 'SAN MIGUEL', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('917', 'SAN VICENTE DE CHUCURI', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('918', 'SANTA BARBARA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('919', 'SANTA HELENA DEL OPON', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('920', 'SIMACOTA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('921', 'SOCORRO', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('922', 'SUAITA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('923', 'SUCRE', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('924', 'SURATA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('925', 'TONA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('926', 'VALLE DE SAN JOSE', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('927', 'VELEZ', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('928', 'VETAS', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('929', 'VILLANUEVA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('930', 'ZAPATOCA', '68');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('931', 'SINCELEJO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('932', 'BUENAVISTA', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('933', 'CAIMITO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('934', 'COLOSO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('935', 'COROZAL', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('936', 'COVEÑAS', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('937', 'CHALAN', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('938', 'EL ROBLE', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('939', 'GALERAS', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('940', 'GUARANDA', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('941', 'LA UNION', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('942', 'LOS PALMITOS', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('943', 'MAJAGUAL', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('944', 'MORROA', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('945', 'OVEJAS', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('946', 'PALMITO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('947', 'SAMPUES', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('948', 'SAN BENITO ABAD', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('949', 'SAN JUAN DE BETULIA', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('950', 'SAN MARCOS', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('951', 'SAN ONOFRE', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('952', 'SAN PEDRO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('953', 'SAN LUIS DE SINCE', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('954', 'SUCRE', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('955', 'SANTIAGO DE TOLU', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('956', 'TOLU VIEJO', '70');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('957', 'IBAGUE', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('958', 'ALPUJARRA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('959', 'ALVARADO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('960', 'AMBALEMA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('961', 'ANZOATEGUI', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('962', 'ARMERO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('963', 'ATACO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('964', 'CAJAMARCA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('965', 'CARMEN DE APICALA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('966', 'CASABIANCA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('967', 'CHAPARRAL', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('968', 'COELLO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('969', 'COYAIMA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('970', 'CUNDAY', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('971', 'DOLORES', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('972', 'ESPINAL', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('973', 'FALAN', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('974', 'FLANDES', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('975', 'FRESNO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('976', 'GUAMO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('977', 'HERVEO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('978', 'HONDA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('979', 'ICONONZO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('980', 'LERIDA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('981', 'LIBANO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('982', 'MARIQUITA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('983', 'MELGAR', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('984', 'MURILLO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('985', 'NATAGAIMA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('986', 'ORTEGA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('987', 'PALOCABILDO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('988', 'PIEDRAS', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('989', 'PLANADAS', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('990', 'PRADO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('991', 'PURIFICACION', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('992', 'RIOBLANCO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('993', 'RONCESVALLES', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('994', 'ROVIRA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('995', 'SALDAÑA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('996', 'SAN ANTONIO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('997', 'SAN LUIS', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('998', 'SANTA ISABEL', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('999', 'SUAREZ', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1000', 'VALLE DE SAN JUAN', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1001', 'VENADILLO', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1002', 'VILLAHERMOSA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1003', 'VILLARRICA', '73');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1004', 'CALI', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1005', 'ALCALA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1006', 'ANDALUCIA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1007', 'ANSERMANUEVO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1008', 'ARGELIA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1009', 'BOLIVAR', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1010', 'BUENAVENTURA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1011', 'GUADALAJARA DE BUGA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1012', 'BUGALAGRANDE', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1013', 'CAICEDONIA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1014', 'CALIMA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1015', 'CANDELARIA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1016', 'CARTAGO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1017', 'DAGUA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1018', 'EL AGUILA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1019', 'EL CAIRO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1020', 'EL CERRITO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1021', 'EL DOVIO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1022', 'FLORIDA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1023', 'GINEBRA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1024', 'GUACARI', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1025', 'JAMUNDI', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1026', 'LA CUMBRE', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1027', 'LA UNION', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1028', 'LA VICTORIA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1029', 'OBANDO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1030', 'PALMIRA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1031', 'PRADERA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1032', 'RESTREPO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1033', 'RIOFRIO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1034', 'ROLDANILLO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1035', 'SAN PEDRO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1036', 'SEVILLA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1037', 'TORO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1038', 'TRUJILLO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1039', 'TULUA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1040', 'ULLOA', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1041', 'VERSALLES', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1042', 'VIJES', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1043', 'YOTOCO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1044', 'YUMBO', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1045', 'ZARZAL', '76');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1046', 'ARAUCA', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1047', 'ARAUQUITA', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1048', 'CRAVO NORTE', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1049', 'FORTUL', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1050', 'PUERTO RONDON', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1051', 'SARAVENA', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1052', 'TAME', '81');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1053', 'YOPAL', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1054', 'AGUAZUL', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1055', 'CHAMEZA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1056', 'HATO COROZAL', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1057', 'LA SALINA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1058', 'MANI', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1059', 'MONTERREY', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1060', 'NUNCHIA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1061', 'OROCUE', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1062', 'PAZ DE ARIPORO', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1063', 'PORE', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1064', 'RECETOR', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1065', 'SABANALARGA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1066', 'SACAMA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1067', 'SAN LUIS DE PALENQUE', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1068', 'TAMARA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1069', 'TAURAMENA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1070', 'TRINIDAD', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1071', 'VILLANUEVA', '85');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1072', 'MOCOA', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1073', 'COLON', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1074', 'ORITO', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1075', 'PUERTO ASIS', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1076', 'PUERTO CAICEDO', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1077', 'PUERTO GUZMAN', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1078', 'LEGUIZAMO', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1079', 'SIBUNDOY', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1080', 'SAN FRANCISCO', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1081', 'SAN MIGUEL', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1082', 'SANTIAGO', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1083', 'VALLE DEL GUAMUEZ', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1084', 'VILLAGARZON', '86');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1085', 'SAN ANDRES', '88');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1086', 'PROVIDENCIA', '88');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1087', 'LETICIA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1088', 'EL ENCANTO', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1089', 'LA CHORRERA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1090', 'LA PEDRERA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1091', 'LA VICTORIA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1092', 'MIRITI - PARANA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1093', 'PUERTO ALEGRIA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1094', 'PUERTO ARICA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1095', 'PUERTO NARIÑO', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1096', 'PUERTO SANTANDER', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1097', 'TARAPACA', '91');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1098', 'INIRIDA', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1099', 'BARRANCO MINAS', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1100', 'MAPIRIPANA', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1101', 'SAN FELIPE', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1102', 'PUERTO COLOMBIA', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1103', 'LA GUADALUPE', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1104', 'CACAHUAL', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1105', 'PANA PANA', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1106', 'MORICHAL', '94');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1107', 'SAN JOSE DEL GUAVIARE', '95');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1108', 'CALAMAR', '95');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1109', 'EL RETORNO', '95');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1110', 'MIRAFLORES', '95');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1111', 'MITU', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1112', 'CARURU', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1113', 'PACOA', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1114', 'TARAIRA', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1115', 'PAPUNAUA', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1116', 'YAVARATE', '97');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1117', 'PUERTO CARREÑO', '99');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1118', 'LA PRIMAVERA', '99');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1119', 'SANTA ROSALIA', '99');
INSERT INTO ciudad (idciudad, nombre_ciudad, iddepartamento) VALUES ('1120', 'CUMARIBO', '99');


--
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('0255', 'JAVIER', '00', '3000000000', NULL, NULL, NULL, NULL, 1, '418', 3, '0255', 120000.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('1064117392', 'JAVIER MENDOZA CASTILLO', 'Diagonal 4 #1F-20, Barrio: 17 de febrero', '3005020032', 'jnegrazo@gmail.com', NULL, NULL, NULL, 1, '418', 2, '1064117392', 7000.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('1064118331', 'SANTIAGO MIGUEL VÁSQUEZ REGUILLO', 'Diagonal 9   #1G-50  Barrio: 17 Febrero', '3012032829', 'santiagomiguel_10@hotmail.com', NULL, NULL, NULL, 1, '418', 1, '1064118331', 80000.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('12524858', 'YORWIN ARIAS REALES', 'Diagonal 5 #1F-05, Barrio: 17 de febrero', '3164079851', 'tarayor@gmail.com', NULL, 'Abonara el Domingo: 80.000', NULL, 1, '418', 2, '12524858', 90000.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('365885', 'SURTIRESPUESTO', 'TRAVS', '3005020032', NULL, NULL, NULL, NULL, 1, '418', NULL, '365885', 65200.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('1064112459', 'WILMAN SAMIR PEREZ MOSCOTE', 'Transversal 1H 9-21 Barrio: 17 febrero', '3137137455', 'wilmanmoscote31@gmail.com', NULL, 'Tel Adicional: 313 5755576', NULL, 1, '418', 1, '1064112459', 23300000.00, NULL);
INSERT INTO clientes (nit_cc, nombre_completo, direccion, tel_movil, email, fax, observacion, tel_fijo, sexo, idciudad, idzona, saldo_nit_cc, cupo_tope, dia_limites_facturas) VALUES ('12354', 'nevo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '418', NULL, '0', 0.00, NULL);


--
-- Data for Name: departamento; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO departamento (iddepartamento, nombre) VALUES ('08', 'ATLANTICO');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('11', 'BOGOTA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('13', 'BOLIVAR');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('15', 'BOYACA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('17', 'CALDAS');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('18', 'CAQUETA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('19', 'CAUCA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('20', 'CESAR');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('23', 'CORDOBA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('25', 'CUNDINAMARCA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('27', 'CHOCO');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('41', 'HUILA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('44', 'LA GUAJIRA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('47', 'MAGDALENA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('50', 'META');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('52', 'NARIÑO');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('54', 'N. DE SANTANDER');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('63', 'QUINDIO');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('66', 'RISARALDA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('68', 'SANTANDER');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('70', 'SUCRE');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('73', 'TOLIMA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('76', 'VALLE DEL CAUCA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('81', 'ARAUCA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('85', 'CASANARE');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('86', 'PUTUMAYO');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('88', 'SAN ANDRES');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('91', 'AMAZONAS');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('94', 'GUAINIA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('95', 'GUAVIARE');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('97', 'VAUPES');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('99', 'VICHADA');
INSERT INTO departamento (iddepartamento, nombre) VALUES ('05', 'ANTIOQUIA');


--
-- Data for Name: descuentos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO descuentos (iddescuentos, descpcion_descuento) VALUES (5.00, '5 %');
INSERT INTO descuentos (iddescuentos, descpcion_descuento) VALUES (0.00, '0%');


--
-- Data for Name: detalle_factura_compra; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO detalle_factura_compra (iddetalle, cantidad, idproducto, idfactura_compra, precio_costo, garantia) VALUES (160, 5000.00, '302', 22, 100.00, NULL);
INSERT INTO detalle_factura_compra (iddetalle, cantidad, idproducto, idfactura_compra, precio_costo, garantia) VALUES (161, 120.00, '302', 17, 2.00, NULL);
INSERT INTO detalle_factura_compra (iddetalle, cantidad, idproducto, idfactura_compra, precio_costo, garantia) VALUES (162, 51.00, '302', 17, 51.00, NULL);
INSERT INTO detalle_factura_compra (iddetalle, cantidad, idproducto, idfactura_compra, precio_costo, garantia) VALUES (163, 1.00, '302', 17, 10.00, NULL);


--
-- Name: detalle_factura_compra_iddetalle_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_compra_iddetalle_seq', 163, true);


--
-- Name: detalle_factura_compra_idfactura_compra_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_compra_idfactura_compra_seq', 1, false);


--
-- Name: detalle_factura_compra_idproducto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_compra_idproducto_seq', 1, false);


--
-- Data for Name: detalle_factura_venta; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO detalle_factura_venta (iddetalle, cantidad, precio_venta_unitario, valor_parcial, idproducto, idventa, descuento, iva, aplicar_descuento, utilidad_para_vendedor) VALUES (2218, 0.50, 130000.00, 65000.00, '302', 242, 0.00, 19.00, NULL, 5);
INSERT INTO detalle_factura_venta (iddetalle, cantidad, precio_venta_unitario, valor_parcial, idproducto, idventa, descuento, iva, aplicar_descuento, utilidad_para_vendedor) VALUES (2219, 0.50, 130000.00, 65000.00, '302', 243, 0.00, 19.00, NULL, 5);
INSERT INTO detalle_factura_venta (iddetalle, cantidad, precio_venta_unitario, valor_parcial, idproducto, idventa, descuento, iva, aplicar_descuento, utilidad_para_vendedor) VALUES (2220, 0.50, 130000.00, 65000.00, '302', 245, 0.00, 19.00, NULL, 5);
INSERT INTO detalle_factura_venta (iddetalle, cantidad, precio_venta_unitario, valor_parcial, idproducto, idventa, descuento, iva, aplicar_descuento, utilidad_para_vendedor) VALUES (2221, 1.00, 130000.00, 130000.00, '302', 246, 0.00, 19.00, NULL, 10);


--
-- Name: detalle_factura_venta_iddetalle_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_venta_iddetalle_seq', 2221, true);


--
-- Name: detalle_factura_venta_idproducto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_venta_idproducto_seq', 1, false);


--
-- Name: detalle_factura_venta_idventa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('detalle_factura_venta_idventa_seq', 1, false);


--
-- Data for Name: deudas_memo; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: deudas_memo_id_deuda_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('deudas_memo_id_deuda_seq', 36, true);


--
-- Data for Name: facturar_ventas; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO facturar_ventas (clientes_nit_cc, idventa, fecha_hora, tipo_de_venta, abono, valor_total_pagar, n_factura, iva_total, descuento_total, saldo, sub_total, estado, user_id, printt, vendedor, utilidad_en_venta, forma_pago) VALUES ('0255', 242, '2017-08-19 00:00:00-05', 2, 0.00, 65000.00, 'hoy', 0.95, 0.00, 65000.00, 64999.05, 2, 1064117392, NULL, NULL, 64995.00, 0);
INSERT INTO facturar_ventas (clientes_nit_cc, idventa, fecha_hora, tipo_de_venta, abono, valor_total_pagar, n_factura, iva_total, descuento_total, saldo, sub_total, estado, user_id, printt, vendedor, utilidad_en_venta, forma_pago) VALUES ('1064118331', 243, '2017-08-18 00:00:00-05', 1, 65000.00, 65000.00, 'ayer', 0.95, 0.00, 0.00, 64999.05, 2, 1064117392, NULL, NULL, 64995.00, 0);
INSERT INTO facturar_ventas (clientes_nit_cc, idventa, fecha_hora, tipo_de_venta, abono, valor_total_pagar, n_factura, iva_total, descuento_total, saldo, sub_total, estado, user_id, printt, vendedor, utilidad_en_venta, forma_pago) VALUES ('1064118331', 244, '2017-08-19 00:00:00-05', 2, 0.00, 0.00, 'ayer2', 0.00, 0.00, 0.00, 0.00, 2, 1064117392, NULL, NULL, 0.00, 0);
INSERT INTO facturar_ventas (clientes_nit_cc, idventa, fecha_hora, tipo_de_venta, abono, valor_total_pagar, n_factura, iva_total, descuento_total, saldo, sub_total, estado, user_id, printt, vendedor, utilidad_en_venta, forma_pago) VALUES ('1064118331', 245, '2017-08-19 00:00:00-05', 2, 0.00, 65000.00, 'hoy', 0.95, 0.00, 65000.00, 64999.05, 2, 1064117392, NULL, NULL, 64995.00, 0);
INSERT INTO facturar_ventas (clientes_nit_cc, idventa, fecha_hora, tipo_de_venta, abono, valor_total_pagar, n_factura, iva_total, descuento_total, saldo, sub_total, estado, user_id, printt, vendedor, utilidad_en_venta, forma_pago) VALUES ('1064117392', 246, '2017-08-20 00:00:00-05', 1, 130000.00, 130000.00, 'ho', 1.90, 0.00, 0.00, 129998.10, 2, 1064117392, NULL, NULL, 129990.00, 0);


--
-- Name: facturar_ventas_idventa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('facturar_ventas_idventa_seq', 1, true);


--
-- Name: idventa; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('idventa', 246, true);


--
-- Data for Name: importacion_excel; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO importacion_excel (id_importacion, url_archivo) VALUES (1, 'data_extermal/importacion_excel/30653.xlsx');
INSERT INTO importacion_excel (id_importacion, url_archivo) VALUES (2, 'data_extermal/importacion_excel/12300.xlsx');
INSERT INTO importacion_excel (id_importacion, url_archivo) VALUES (3, 'data_extermal/importacion_excel/9803.xlsx');
INSERT INTO importacion_excel (id_importacion, url_archivo) VALUES (4, 'data_extermal/importacion_excel/5059.xlsx');


--
-- Name: importacion_excel_id_importacion_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('importacion_excel_id_importacion_seq', 4, true);


--
-- Data for Name: iva; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO iva (id_iva, descripcion) VALUES (0.00, '0 %');
INSERT INTO iva (id_iva, descripcion) VALUES (19.00, '19 %');


--
-- Name: n_factura; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('n_factura', 144, true);


--
-- Data for Name: phpgen_user_perms; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO phpgen_user_perms (user_id, page_name, perm_name) VALUES (1064117392, '', 'ADMIN');
INSERT INTO phpgen_user_perms (user_id, page_name, perm_name) VALUES (523, 'public.productos', 'SELECT');
INSERT INTO phpgen_user_perms (user_id, page_name, perm_name) VALUES (523, 'public.productos', 'UPDATE');
INSERT INTO phpgen_user_perms (user_id, page_name, perm_name) VALUES (523, 'public.productos', 'INSERT');
INSERT INTO phpgen_user_perms (user_id, page_name, perm_name) VALUES (523, 'public.productos', 'DELETE');


--
-- Data for Name: phpgen_users; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO phpgen_users (user_id, user_name, user_password) VALUES (1064117392, 'admin', '2290a7385ed77cc5592dc2153229f082');
INSERT INTO phpgen_users (user_id, user_name, user_password) VALUES (523, 'new', 'cfcd208495d565ef66e7dff9f98764da');
INSERT INTO phpgen_users (user_id, user_name, user_password) VALUES (1650958, 'usuario', 'cfcd208495d565ef66e7dff9f98764da');


--
-- Data for Name: productos; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('ASDAS32', 'Ya', NULL, 1, 20000, 200, 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 118800, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('296', 'Reloj Lugo 2017 negro acero inoxidable unisex', 1, 1, 130000, 30000, 300, 84, 19.00, NULL, 2, 17, 0.00, NULL, 'img_productos/11099.jpg', 5000, 5.00);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('ASDA65', 'YUCA', NULL, 1, 50000, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('302', '<img src="img_productos/22256.png" width="20" height="20" />Reloj Lujo 2017 Negro acero inoxidable unisex Calendario', 1, 1, 130000, 10, 4921, 83, 19.00, NULL, 4, 20, 0.00, NULL, 'img_productos/22256.png', 10, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('300', '<img src="img_productos/8523.jpg" width="20" height="20" />Reloj deportivo masculino acero inoxidable', 1, 1, 180000, 160000, 0, 83, 19.00, NULL, 4, 19, 0.00, NULL, 'img_productos/8523.jpg', 0, 10.00);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('A3ACXS12', 'Dulces', NULL, 1, 100, 200, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('301', '<img src="img_productos/28355.jpg" width="20" height="20" />Reloj lujo casual automático', 1, 1, 135000, 100000, 0, 84, 19.00, NULL, 4, 19, 0.00, NULL, 'img_productos/28355.jpg', 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('ASD651', 'Pan', NULL, 1, 100, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('ASD65A6S', 'Papas', NULL, 1, 100, 205, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('297', '<img src="img_productos/26514.jpg" width="20" height="20" />Reloj Lugo 2017 oro acero inoxidable unisex', 1, 1, 130000, 115000, 0, 84, 19.00, NULL, 4, 17, 0.00, NULL, 'img_productos/26514.jpg', 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('303', '<img src="img_productos/2723.jpg" width="20" height="20" />Reloj Lugo 2017 negro acero inoxidable unisex', 1, 1, 130000, 115000, 0, 84, 19.00, NULL, 4, 21, 0.00, NULL, 'img_productos/2723.jpg', 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('293', '<img src="img_productos/11399.jpg" width="20" height="20" />Reloj de Lujo  2017 Masculino', 1, 1, 130000, 120000, 0, 86, 19.00, NULL, 4, 15, 0.00, NULL, 'img_productos/11399.jpg', 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('294', '<img src="img_productos/23668.png" width="20" height="20" />Reloj de Lujo  2017 Masculino', 1, 1, 120000, 100000, 1, 86, 19.00, NULL, 4, 15, 0.00, NULL, 'img_productos/23668.png', 0, NULL);
INSERT INTO productos (idproducto, nombre, stock_minimo, estado, precio_venta, precio_costo, stock, idcaracter, id_iva, idtipo, idunidad, idfactura_compra, iddescuentos, estado_stock, img, utilidad_para_vendedor, porcentaje_utilidad_vendedor) VALUES ('295', '<img src="img_productos/28258.jpg" width="20" height="20" /> Reloj de Lujo, cuero 2016 Femenino', 1, 1, 120000, 100000, 1, 87, 19.00, NULL, 4, 16, 0.00, NULL, 'img_productos/28258.jpg', 0, 25.36);


--
-- Name: productos_idcaracter_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('productos_idcaracter_seq', 1, true);


--
-- Name: productos_idfactura_compra_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('productos_idfactura_compra_seq', 1, true);


--
-- Name: productos_idproducto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('productos_idproducto_seq', 338, true);


--
-- Name: productos_idtipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('productos_idtipo_seq', 1, true);


--
-- Name: productos_idunidad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('productos_idunidad_seq', 1, true);


--
-- Data for Name: proveedor; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('1683210', 'SOHO Watch Store', NULL, 'Guangdong China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('605842', 'LIGE Official Store', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('1180375', 'GOOD LUCK store', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('1892432', 'B-KTime Store', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('2034018', 'Fashisual', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('132940', 'Besunny watch Store', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');
INSERT INTO proveedor (nit_cc, nombre_comercial, n_cuenta, direccion, tel_movil, email, fax, observacion, tel_fijo, idciudad) VALUES ('1962801', 'THE FIRST SKY Store', NULL, 'China (Mainland)', NULL, NULL, NULL, NULL, NULL, '149');


--
-- Data for Name: registrar_compra; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84256842680650 ', 1, '1683210', '2017-05-31', '2017-06-27', 17, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84256842610650 ', 1, '132940', '2017-05-31', '2017-06-27', 16, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84256842580650', 1, '1962801', '2017-05-31', '2017-06-27', 15, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84954359730650 ', 1, '1683210', '2017-06-28', NULL, 21, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84954359720650 ', 1, '1180375', '2017-06-28', NULL, 20, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84256842650650', 1, '1892432', '2017-05-31', '2017-07-05', 19, NULL);
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('84256842620650 ', 1, '2034018', '2017-05-31', '2017-07-05', 18, 'NO COMPRAR, UN PRODUCTO NO LLEGO');
INSERT INTO registrar_compra (num_factura, estado, id_proveedor, fecha_de_compra, fecha_de_pago, idfactura_compra, nota) VALUES ('11564', 1, '1892432', '2017-07-31', '2017-07-31', 22, NULL);


--
-- Name: registrar_compra_idfactura_compra_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('registrar_compra_idfactura_compra_seq', 22, true);


--
-- Data for Name: tipo_productos; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: tipo_productos_idtipo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tipo_productos_idtipo_seq', 1, true);


--
-- Data for Name: unidades; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO unidades (idunidad, descripcion) VALUES (2, 'CAJA');
INSERT INTO unidades (idunidad, descripcion) VALUES (3, 'PAQUETE');
INSERT INTO unidades (idunidad, descripcion) VALUES (4, 'UNIDAD');


--
-- Name: unidades_idunidad_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('unidades_idunidad_seq', 9, true);


--
-- Data for Name: zona; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO zona (idzona, descripcion, saldo_zona) VALUES (2, 'ZONA 2 ', 2);
INSERT INTO zona (idzona, descripcion, saldo_zona) VALUES (1, 'ZONA 1 ', 1);
INSERT INTO zona (idzona, descripcion, saldo_zona) VALUES (3, 'ZONA 3', NULL);


--
-- Name: zona_idzona_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('zona_idzona_seq', 3, true);


--
-- Name: caracteristicas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY caracteristicas
    ADD CONSTRAINT caracteristicas_pkey PRIMARY KEY (idcaracter);


--
-- Name: ciudad_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY ciudad
    ADD CONSTRAINT ciudad_pkey PRIMARY KEY (idciudad);


--
-- Name: clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (nit_cc);


--
-- Name: departamento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY departamento
    ADD CONSTRAINT departamento_pkey PRIMARY KEY (iddepartamento);


--
-- Name: descuentos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY descuentos
    ADD CONSTRAINT descuentos_pkey PRIMARY KEY (iddescuentos);


--
-- Name: detalle_factura_compra_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY detalle_factura_compra
    ADD CONSTRAINT detalle_factura_compra_pkey PRIMARY KEY (iddetalle);


--
-- Name: detalle_factura_venta_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY detalle_factura_venta
    ADD CONSTRAINT detalle_factura_venta_pkey PRIMARY KEY (iddetalle);


--
-- Name: facturar_ventas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY facturar_ventas
    ADD CONSTRAINT facturar_ventas_pkey PRIMARY KEY (idventa);


--
-- Name: iva_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iva
    ADD CONSTRAINT iva_pkey PRIMARY KEY (id_iva);


--
-- Name: phpgen_user_perms_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY phpgen_user_perms
    ADD CONSTRAINT phpgen_user_perms_pkey PRIMARY KEY (user_id, page_name, perm_name);


--
-- Name: phpgen_users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY phpgen_users
    ADD CONSTRAINT phpgen_users_pkey PRIMARY KEY (user_id);


--
-- Name: pk_id_deuda; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY deudas_memo
    ADD CONSTRAINT pk_id_deuda PRIMARY KEY (id_deuda);


--
-- Name: pk_idzona; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY zona
    ADD CONSTRAINT pk_idzona PRIMARY KEY (idzona);


--
-- Name: pkid_importacion; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY importacion_excel
    ADD CONSTRAINT pkid_importacion PRIMARY KEY (id_importacion);


--
-- Name: productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT productos_pkey PRIMARY KEY (idproducto);


--
-- Name: proveedor_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT proveedor_pkey PRIMARY KEY (nit_cc);


--
-- Name: registrar_compra_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY registrar_compra
    ADD CONSTRAINT registrar_compra_pkey PRIMARY KEY (idfactura_compra);


--
-- Name: tipo_productos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tipo_productos
    ADD CONSTRAINT tipo_productos_pkey PRIMARY KEY (idtipo);


--
-- Name: unidades_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY unidades
    ADD CONSTRAINT unidades_pkey PRIMARY KEY (idunidad);


--
-- Name: _RETURN; Type: RULE; Schema: public; Owner: postgres
--

CREATE RULE "_RETURN" AS
    ON SELECT TO cliente_abono DO INSTEAD  SELECT clientes.nit_cc,
    (((clientes.nit_cc)::text || ', '::text) || (clientes.nombre_completo)::text) AS nombre
   FROM clientes,
    facturar_ventas
  WHERE (((facturar_ventas.clientes_nit_cc)::text = (clientes.nit_cc)::text) AND (facturar_ventas.saldo <> (0)::numeric))
  GROUP BY clientes.nit_cc
  ORDER BY clientes.nombre_completo;


--
-- Name: _RETURN; Type: RULE; Schema: public; Owner: postgres
--

CREATE RULE "_RETURN" AS
    ON SELECT TO cliente_saldo_vi DO INSTEAD  SELECT clientes.nit_cc,
    clientes.nombre_completo,
    sum(facturar_ventas.saldo) AS saldo,
    clientes.tel_movil,
    clientes.direccion,
    clientes.email,
    clientes.observacion,
    clientes.tel_fijo,
    clientes.idciudad,
    clientes.idzona
   FROM clientes,
    zona,
    facturar_ventas
  WHERE (((clientes.nit_cc)::text = (facturar_ventas.clientes_nit_cc)::text) AND (zona.idzona = clientes.idzona))
  GROUP BY clientes.nit_cc;


--
-- Name: Ref_ciudad_to_departamento; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY ciudad
    ADD CONSTRAINT "Ref_ciudad_to_departamento" FOREIGN KEY (iddepartamento) REFERENCES departamento(iddepartamento);


--
-- Name: Ref_clientes_has_productos_to_clientes; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY facturar_ventas
    ADD CONSTRAINT "Ref_clientes_has_productos_to_clientes" FOREIGN KEY (clientes_nit_cc) REFERENCES clientes(nit_cc);


--
-- Name: Ref_clientes_to_ciudad; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clientes
    ADD CONSTRAINT "Ref_clientes_to_ciudad" FOREIGN KEY (idciudad) REFERENCES ciudad(idciudad);


--
-- Name: Ref_detalle_factura_compra_to_productos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_compra
    ADD CONSTRAINT "Ref_detalle_factura_compra_to_productos" FOREIGN KEY (idproducto) REFERENCES productos(idproducto);


--
-- Name: Ref_detalle_factura_compra_to_registrar_compra; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_compra
    ADD CONSTRAINT "Ref_detalle_factura_compra_to_registrar_compra" FOREIGN KEY (idfactura_compra) REFERENCES registrar_compra(idfactura_compra);


--
-- Name: Ref_detalle_factura_venta_to_facturar_ventas; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_venta
    ADD CONSTRAINT "Ref_detalle_factura_venta_to_facturar_ventas" FOREIGN KEY (idventa) REFERENCES facturar_ventas(idventa);


--
-- Name: Ref_detalle_factura_venta_to_productos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY detalle_factura_venta
    ADD CONSTRAINT "Ref_detalle_factura_venta_to_productos" FOREIGN KEY (idproducto) REFERENCES productos(idproducto);


--
-- Name: Ref_facturas_compra_to_proveedor; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY registrar_compra
    ADD CONSTRAINT "Ref_facturas_compra_to_proveedor" FOREIGN KEY (id_proveedor) REFERENCES proveedor(nit_cc);


--
-- Name: Ref_productos_to_caracteristicas; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_caracteristicas" FOREIGN KEY (idcaracter) REFERENCES caracteristicas(idcaracter);


--
-- Name: Ref_productos_to_descuentos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_descuentos" FOREIGN KEY (iddescuentos) REFERENCES descuentos(iddescuentos);


--
-- Name: Ref_productos_to_iva; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_iva" FOREIGN KEY (id_iva) REFERENCES iva(id_iva);


--
-- Name: Ref_productos_to_registrar_compra; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_registrar_compra" FOREIGN KEY (idfactura_compra) REFERENCES registrar_compra(idfactura_compra);


--
-- Name: Ref_productos_to_tipo_productos; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_tipo_productos" FOREIGN KEY (idtipo) REFERENCES tipo_productos(idtipo);


--
-- Name: Ref_productos_to_unidades; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY productos
    ADD CONSTRAINT "Ref_productos_to_unidades" FOREIGN KEY (idunidad) REFERENCES unidades(idunidad);


--
-- Name: Ref_proveedor_to_ciudad; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY proveedor
    ADD CONSTRAINT "Ref_proveedor_to_ciudad" FOREIGN KEY (idciudad) REFERENCES ciudad(idciudad);


--
-- Name: fk_idzona; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clientes
    ADD CONSTRAINT fk_idzona FOREIGN KEY (idzona) REFERENCES zona(idzona);


--
-- Name: fk_nit_cc; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY deudas_memo
    ADD CONSTRAINT fk_nit_cc FOREIGN KEY (nit_ccc) REFERENCES clientes(nit_cc);


--
-- Name: fkfactura_venta; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY deudas_memo
    ADD CONSTRAINT fkfactura_venta FOREIGN KEY (idventa) REFERENCES facturar_ventas(idventa);


--
-- Name: fkusers; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY facturar_ventas
    ADD CONSTRAINT fkusers FOREIGN KEY (user_id) REFERENCES phpgen_users(user_id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

