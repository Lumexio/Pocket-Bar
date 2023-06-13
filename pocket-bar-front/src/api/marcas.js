import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getMarcas(marcaArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/marca")
      .then(response => {
        const marca = response.data.marcas;
        const stats = response.status;
        marca.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_marca: element.nombre_marca,
            active:element.active,
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
    .post("api/marca", enviar, {
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
  axios.put("api/marca/activate/" + id).catch((error) => console.log(error));
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