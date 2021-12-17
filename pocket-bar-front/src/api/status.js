import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getStatus(statusArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/status")
      .then(response => {
        const status = response.data;
        const stats = response.status;
        status.forEach((element) => {
          let datos = {
            status_id: element.id,
            nombre_status: element.nombre_status,
          };
          if (!datos) return;
          statusArray.push(datos);
        });
        resolve({
          stats, statusArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postStatus(enviar) {
  axios
    .post("api/status", enviar)
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
export function deleteStatus(id) {
  axios.delete("api/status/" + id).catch((error) => console.log(error));
}
export function editStatus(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getStatus, postStatus, deleteStatus, editStatus }