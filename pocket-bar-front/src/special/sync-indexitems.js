export function tiposync(itemstt, selectt, recived) {
 var tempid = "";
 var tempname = "";
 tempname;

 if (itemstt) {
  let tipo = itemstt;

  tipo.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_tipo: element.nombre_tipo,
   };
   if (datos.nombre_tipo === recived) {
    tempid = datos.id;
    tempname = datos.nombre_tipo;
    selectt = tempid;
   }
  });
 }

 return selectt;
}
export function proveedorsync(itemsp, selectp, recived) {
 var tempid = "";
 var tempname = "";
 tempname;

 if (itemsp) {
  let proveedor = itemsp;
  proveedor.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_proveedor: element.nombre_proveedor,
   };
   if (datos.nombre_proveedor === recived) {
    tempid = datos.id;
    tempname = datos.nombre_proveedor;

    selectp = tempid;
   }
  });
 }
 return selectp;
}
export function marcasync(itemstm, selectm, recived) {
 var tempid = "";
 var tempname = "";
 tempname;

 if (itemstm) {
  let marca = itemstm;
  marca.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_marca: element.nombre_marca,
   };
   if (datos.nombre_marca === recived) {
    tempid = datos.id;
    tempname = datos.nombre_marca;

    selectm = tempid;
   }
  });
 }
 return selectm;
}
export function statusync(itemstst, selectst, recived) {
 var tempid = "";
 var tempname = "";

 tempname;
 if (itemstst) {
  let status = itemstst;
  status.forEach((element) => {
   let datos = {
    status_id: element.status_id,
    nombre_status: element.nombre_status,
   };

   if (datos.nombre_status === recived) {
    tempid = datos.status_id;
    tempname = datos.nombre_status;

    selectst = tempid;
   }
  });
 }
 return selectst;
}
export function racksync(itemsr, selectr, recived) {
 var tempid = "";
 var tempname = "";
 tempname;
 if (itemsr) {
  let rack = itemsr;
  rack.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_rack: element.nombre_rack,
   };
   if (datos.nombre_rack === recived) {
    tempid = datos.id;
    tempname = datos.nombre_rack;

    selectr = tempid;
   }
  });
 }
 return selectr;
}
export function travesañosync(itemsT, selectT, recived) {
 var tempid = "";
 var tempname = "";
 tempname;
 if (itemsT) {
  let rack = itemsT;
  rack.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_travesano: element.nombre_travesano,
   };
   if (datos.nombre_travesano === recived) {
    tempid = datos.id;
    tempname = datos.nombre_travesano;

    selectT = tempid;
   }
  });
 }
 return selectT;
}
export function categsync(itemsc, selectc, recived) {

 var tempid = "";
 var tempname = "";
 tempname;
 if (itemsc) {
  let categoria = itemsc;
  categoria.forEach((element) => {
   let datos = {
    id: element.id,
    nombre_categoria: element.nombre_categoria,
   };
   if (datos.nombre_categoria === recived) {
    tempid = datos.id;
    tempname = datos.nombre_categoria;

    selectc = tempid;
   }
  });
 }
 return selectc;
}

export default { tiposync, categsync, statusync, travesañosync, racksync, marcasync, proveedorsync }