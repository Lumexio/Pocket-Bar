import axios from "axios";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname + ":8000";
export function getRol(itemsrol) {
 return new Promise((resolve, reject) => {
  axios
   .get("api/rol")
   .then((response) => {
    let categorias = response.data;
    let stats = response.status;

    categorias.forEach((element) => {
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
    console.log(e.message);
   });
 })
}
export default { getRol };