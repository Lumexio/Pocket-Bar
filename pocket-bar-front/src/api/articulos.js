import axios from "axios";
import store from "@/store";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";



export function getArticulos(articulosArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/articulo/list")
      .then((response) => {
        const articulos = response.data.articulos;
        const stats = response.status;
        articulos.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_articulo: element.nombre_articulo,
            cantidad_articulo: element.cantidad_articulo,
            descripcion_articulo: element.descripcion_articulo, //pendiente
            precio_articulo: element.precio_articulo, //pendiente
            nombre_categoria: element.nombre_categoria,
            nombre_tipo: element.nombre_tipo,
            nombre_marca: element.nombre_marca,
            nombre_proveedor: element.nombre_proveedor,
            nombre_status: element.nombre_status,
            deactivated_at: element.deactivated_at,
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
      store.commit("setsuccess", false);
      store.commit("setsuccessMessage", "");
      if (response.statusText === "Created") {
        store.commit("setsuccess", true);
        store.commit("setsuccessMessage", "Articulo creado correctamente");

      }
    })
    .catch((e) => {
      store.commit("setdanger", false);
      store.commit("setdanger-message", "");
      if (e) {
        store.commit("setdanger", true);
        store.commit("setdanger-message", "Error al crear el articulo");
      }
    });
}
export function activateArticulos(id) {
  return new Promise((resolve, reject) => {
    axios.put("api/articulo/activate/" + id).then((response) => {
      resolve(response);
    }).catch((error) => reject(error));
  });
}
export function editArticulos(url, data) {

  axios
    .put(url, data)
    .then((response) => {

      if (response.statusText === "Created") {
        store.commit("setsuccess", true);
      }
    })
    .catch((error) => console.log(error));
}

export default { getArticulos, postArticulos, activateArticulos, editArticulos }