import axios from "axios";
import store from "@/store";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";



export function getArticulos(articulosArray) {
  console.log("getArticulos");
  return new Promise((resolve, reject) => {
    axios
      .get("api/product/")
      .then((response) => {
        const articulos = response.data.articulos;
        const stats = response.status;
        articulos.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_articulo: element.name,
            cantidad_articulo: element.quantity,
            descripcion_articulo: element.description, //pendiente
            precio_articulo: element.price, //pendiente
            nombre_categoria: element.name_categoria,
            nombre_tipo: element.name_tipo,
            nombre_marca: element.name_marca,
            nombre_proveedor: element.name_proveedor,
            nombre_status: element.name_status,
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
    .post("api/product/", enviar, {
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
    axios.put("api/product/activate/" + id).then((response) => {
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