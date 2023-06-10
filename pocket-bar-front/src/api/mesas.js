import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getMesas(mesaArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/mesa")
      .then(response => {

        const mesa = response.data.mesas;
        const stats = response.status;
        
        mesa.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_mesa: element.nombre_mesa,
            descripcion_mesa: element.descripcion_mesa,
          };
          if (!datos) return;
          mesaArray.push(datos);
        });
        resolve({
          stats, mesaArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postMesas(enviar) {

  axios
    .post("api/mesa", enviar, {
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

      if (e) {
        store.commit("setdanger", true);
      }
    });
}
export function deleteMesa(id) {
  axios.delete("api/mesa/" + id).catch((error) => console.log(error));
}
export function editMesa(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getMesas, postMesas, deleteMesa, editMesa }