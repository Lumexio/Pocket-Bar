import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getRack(rackArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/rack")
      .then(response => {
        const rack = response.data;
        const stats = response.status;
        rack.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_rack: element.nombre_rack,
          };
          if (!datos) return;
          rackArray.push(datos);
        });
        resolve({
          stats, rackArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postRack(enviar) {
  axios
    .post("api/rack", enviar)
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
export function deleteRack(id) {
  axios.delete("api/rack/" + id).catch((error) => console.log(error));
}
export function editRack(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getRack, postRack, deleteRack, editRack }