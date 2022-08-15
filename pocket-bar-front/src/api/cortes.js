import axios from "axios";
//import store from "@/store";
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
  console.log("Pack de entrada cierre:", pack);
  return new Promise((resolve, reject) => {
    axios
      .post("api/caja/close", pack)
      .then(response => {
        console.log("Close response:", response);
        resolve({
          response
        });
      })
      .catch((error) => { reject(error); });
  });
}




export default { getCotizado, postCerrarticket }