SELECT  idventa, ('Factura N°'||n_factura ||', Saldo: '|| saldo )as factura_saldo 
  FROM facturar_ventas

WHERE clientes_nit_cc='1064117392' 


AND saldo!=0
  
