import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getTipos(tipoArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/tipo")
      .then(response => {
        const tipo = response.data.tipos;
        const stats = response.status;
        tipo.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_tipo: element.nombre_tipo,
            active: element.active,
          };
          if (!datos) return;
          tipoArray.push(datos);
        });
        
        resolve({
          stats, tipoArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postTipos(enviar) {
  axios
    .post("api/tipo", enviar)
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
export function activationTipos(id) {
  return new Promise((resolve, reject) => {
    axios.put("api/tipo/activate/" + id).then((response) => {
      resolve(response);
    }).catch((error) => reject(error) );
  });
}
export function editTipos(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getTipos, postTipos, activationTipos, editTipos }