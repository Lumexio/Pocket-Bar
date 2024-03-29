import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getMarcas(marcaArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/brand")
      .then(response => {
        const marca = response.data.marcas;
        console.log(marca);
        const stats = response.status;
        marca.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_marca: element.name,
            active: element.active,
          };
          if (!datos) return;
          marcaArray.push(datos);
        });
        resolve({
          stats, marcaArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postMarcas(enviar) {
  axios
    .post("api/brand", enviar, {
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
      store.commit("setdanger", true);
    });
}
export function avtivationMarcas(id) {
  return new Promise((resolve, reject) => {
    axios.put("api/brand/activate/" + id).then((response) => {
      response;
      resolve(response);
    }).catch((error) => console.log(reject(error)));
  });
}
export function editMarcas(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getMarcas, postMarcas, avtivationMarcas, editMarcas }