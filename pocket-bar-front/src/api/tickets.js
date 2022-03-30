import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getTickets(ticketsArray) {

  return new Promise((resolve, reject) => {
    axios
      .get("api/tickets/list")
      .then(response => {

        const tickets = response.data.data;
        const stats = response.status;

        tickets.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_mesero: element.user_name,
            ticket_date: element.ticket_date,
            monto_total: element.total,
            status_ticket: element.status,
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


export function getTicketsPWA(ticketsPWAArray, status) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/tickets/pwa/list", {
        params: {
          status: status
        }
      })
      .then(response => {

        const tickets = response.data.data;
        const stats = response.status;

        tickets.forEach((element) => {
          let datos = {
            id: element.id,
            fecha: element.fecha,
            titular: element.titular,
            total_actual: element.total,
            productos: element.productos,
            mesa: element.mesa,
          };
          if (!datos) return;
          ticketsPWAArray.push(datos);
        });


        resolve({
          stats, ticketsPWAArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}


export default { getTickets, postTickets, getTicketsPWA }