import axios from "axios";
import store from "@/store";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getArticulos(articulosArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/articulo/list")
      .then((response) => {
        const articulos = response.data;
        const stats = response.status;
        articulos.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_articulo: element.nombre_articulo,
            cantidad_articulo: element.cantidad_articulo,
            descripcion_articulo: element.descripcion_articulo, //pendiente
            nombre_categoria: element.nombre_categoria,
            nombre_tipo: element.nombre_tipo,
            nombre_marca: element.nombre_marca,
            nombre_proveedor: element.nombre_proveedor,
            nombre_status: element.nombre_status,
            //campos de ubicación
            nombre_rack: element.nombre_rack,
            nombre_travesano: element.nombre_travesano,
            foto_articulo: element.foto_articulo,
          };
          if (!datos) return;
          articulosArray.push(datos);
        });
        resolve({
          stats, articulosArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postArticulos(enviar) {
  axios
    .post("api/articulo/create/", enviar, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    .then((response) => {
      if (response.statusText === "Created") {
        store.commit("setsuccess", true);

      }
    })
    .catch((e) => {
      console.log(e.message);
      if (e) {
        store.commit("setdanger", true);
      }
    });
}
export function deleteArticulos(id) {
  axios.delete("api/articulo/delete/" + id).then((response) => { response; /*store.commit("increment", 1);*/ }).catch((error) => console.log(error));
}
export function editArticulos(url, data) {
  axios
    .put(url, data, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    .then((response) => {
      console.log("Datos de edit:", response);
      if (response.statusText === "Created") {
        store.commit("setsuccess", true);
      }
      //store.commit("increment", 1);
    })
    .catch((error) => console.log(error));
}

export default { getArticulos, postArticulos, deleteArticulos, editArticulos }