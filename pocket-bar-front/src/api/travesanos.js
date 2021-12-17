import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getTravesano(travesanoArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/travesano")
      .then(response => {
        const Travesano = response.data;
        const stats = response.status;
        Travesano.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_travesano: element.nombre_travesano,
          };
          if (!datos) return;
          travesanoArray.push(datos);
        });
        resolve({
          stats, travesanoArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postTravesano(enviar) {
  axios
    .post("api/travesano", enviar)
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
export function deleteTravesano(id) {
  axios.delete("api/travesano/" + id).catch((error) => console.log(error));
}
export function editTravesano(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getTravesano, postTravesano, deleteTravesano, editTravesano }