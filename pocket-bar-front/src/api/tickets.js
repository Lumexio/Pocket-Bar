import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getTickets(ticketsArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/ticket/" + 1)
      .then(response => {
        console.log(response);
        const tickets = response.data;
        const stats = response.status;
        tickets.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_categoria: element.nombre_categoria,
            descripcion_categoria: element.descripcion_categoria,
          };
          if (!datos) return;
          ticketsArray.push(datos);
        });
        resolve({
          stats, ticketsArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postTickets(enviar) {

  axios
    .post("api/ticket/", enviar, {
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


export default { getTickets, postTickets }