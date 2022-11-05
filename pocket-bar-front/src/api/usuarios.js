import axios from "axios";

axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + /*window.location.hostname*/"127.0.0.1" + ":8000";
export function getUsuarios(usersArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/user")
      .then((response) => {
        let user = response.data;
        let stats = response.status;
        user.forEach((element) => {
          let datos = {
            id: element.id,
            name: element.name,
            name_rol: element.name_rol,
            nominas: element.nominas,
          };
          if (!datos) return;
          usersArray.push(datos);
        });

        resolve({
          usersArray, stats
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  })
}
export default { getUsuarios };