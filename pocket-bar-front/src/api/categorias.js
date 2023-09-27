import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getCategorias(categoriaArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/category")
      .then(response => {
        const categoria = response.data.categorias;
        const stats = response.status;

        categoria.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_categoria: element.name,
            descripcion_categoria: element.descripcion_categoria,
            active: element.active,

          };
          if (!datos) return;
          categoriaArray.push(datos);
        });

        resolve({
          stats, categoriaArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postCategorias(enviar) {

  axios
    .post("api/category", enviar, {
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
export function activateCategoria(id) {
  return new Promise((resolve, reject) => {
    axios.put("api/category/activate/" + id).then((response) => {
      response;
      resolve(response);
    }).catch((error) => console.log(reject(error)));
  });
}
export function editCategoria(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getCategorias, postCategorias, activateCategoria, editCategoria }