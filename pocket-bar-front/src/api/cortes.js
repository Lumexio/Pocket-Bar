import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname + ":8000";


export function getCotizado() {

  return new Promise((resolve, reject) => {
    axios
      .get("api/caja/mustbe")
      .then(response => {
        console.log("Must be:", response);
        resolve({
          response
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postCerrarticket(pack) {

  return new Promise((resolve, reject) => {
    axios
      .post("api/tickets/pay", pack)
      .then(response => {
        if (response.status === 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
            store.commit("setstatcode", null);
          }, 2000);
        }
        resolve({
          response
        });
      })
      .catch((error) => {
        reject(error);
        if (error) {
          store.commit("setdanger", true);
        }
      });
  });
}




export default { getCotizado, postCerrarticket }