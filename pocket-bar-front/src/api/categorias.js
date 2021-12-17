import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getCategorias(categoriaArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/categoria")
      .then(response => {
        const categoria = response.data;
        const stats = response.status;
        categoria.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_categoria: element.nombre_categoria,
            descripcion_categoria: element.descripcion_categoria,
          };
          if (!datos) return;
          categoriaArray.push(datos);
        });
        resolve({
          stats, categoriaArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postCategorias(enviar) {

  axios
    .post("api/categoria", enviar, {
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
export function deleteCategoria(id) {
  axios.delete("api/categoria/" + id).catch((error) => console.log(error));
}
export function editCategoria(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getCategorias, postCategorias, deleteCategoria, editCategoria }