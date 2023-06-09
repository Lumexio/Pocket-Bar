import axios from "axios";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";
export function getRol(itemsrol) {
 return new Promise((resolve, reject) => {
  axios
   .get("api/rol")
    .then((response) => {
    let rols = response.data.data;
    let stats = response.status;
    rols.forEach((element) => {
     let datos = {
      rol_id: element.id,
      name_rol: element.name_rol,
     };
     if (!datos) return;
     itemsrol.push(datos);
    });
    resolve({
     itemsrol, stats
    });
   })
    .catch((e) => {
      reject({
        e
      });

   });
 })
}
export default { getRol };