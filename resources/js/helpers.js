import moment from "moment-timezone";
import _ from "lodash";
import { useAppStore } from "@/store";

export function timeFormat(time, format = "H:i A") {
    return moment(time, "HH:mm:ss").format("LT");
}

export function dateFormat(
    date,
    from_format = "YYYY-MM-DD",
    to_format = "DD/MM/YYYY"
) {
    if (!date) {
        return "<i>N/A</i>";
    }
    if (locale == "en") {
        to_format = "MM/DD/YYYY";
    }
    return moment(date, from_format).format(to_format);
}

export function dateTimeHumanize(dateTime) {
    if (dateTime) {
        return moment(dateTime).fromNow();
    }
    return null;
}

export function dateTimeFormat(dateTime) {
    let to_format = "DD/MM/YYYY hh:mm A";
    if (locale == "en") {
        to_format = "MM/DD/YYYY hh:mm A";
    }
    let client_tz = moment.tz.guess() ? moment.tz.guess() : SERVER_TZ;
    return moment.tz(dateTime, client_tz).format(to_format);
}

export function dateTimeFormatServer(dateTime) {
  let to_format = "YYYY-MM-DD hh:mm A";
  let client_tz = moment.tz.guess() ? moment.tz.guess() : SERVER_TZ;
  return moment.tz(dateTime, client_tz).format(to_format);
}

function addZeroes(num) {
    num = typeof num == "number" ? num.toString() : num;
    const dec = num.split(".")[1];
    const len = 2;
    return Number(num).toFixed(len);
}

export function getItemCost(basePrice, disType, disValue) {
    let discountAmount = 0;
    discountAmount =
        disType == "percentage" ? basePrice * (disValue / 100) : disValue;
    if(discountAmount > basePrice) {
      return addZeroes(0);
    }
    return addZeroes(basePrice - discountAmount);
}

export function openWindowWithPost(url, data) {
    let dataForm = document.createElement("form");
    dataForm.style.display = "none";
    dataForm.target = "_blank"; //Make sure the window name is same as this value
    dataForm.method = "POST";
    dataForm.action = url;
    for (let key in data) {
        let postData = document.createElement("input");
        postData.type = "hidden";
        postData.name = key;
        postData.value = data[key];
        dataForm.appendChild(postData);
    }
    document.body.appendChild(dataForm);
    dataForm.submit();
}

export const trans = (string, args) => {
    let value = _.get(window.i18n, string);

    let defaultVal = _.last(_.split(string, "."));
    if (value) {
        _.eachRight(args, (paramVal, paramKey) => {
            value = _.replace(value, `:${paramKey}`, paramVal);
        });
    } else {
        value = defaultVal;
    }
    return value;
};

export const convertJsonToFormData = (data) => {
    const formData = new FormData()
    const entries = Object.entries(data) // returns array of object property as [key, value]
  
    for (const element of entries) {
      // don't try to be smart by replacing it with entries.each, it has drawbacks
      const arKey = element[0]
      let arVal = element[1]
      if (typeof arVal === 'boolean') {
        arVal = arVal === true ? 1 : 0
      }
      if (Array.isArray(arVal)) {
       
        // if (this.isFile(arVal[0])) {
        //   for (const el of arVal) {
        //     formData.append(`${arKey}[]`, el)
        //   }

        //   continue // we don't need to append current element now, as its elements already appended
        // } else
        if (arVal[0] instanceof Object) {
          for (let j = 0; j < arVal.length; j++) {
            if (arVal[j] instanceof Object) {
              // if first element is not file, we know its not files array
              for (const prop in arVal[j]) {
                if (Object.prototype.hasOwnProperty.call(arVal[j], prop)) {
                  // do stuff
                  if (!isNaN(Date.parse(arVal[j][prop]))) {
                    formData.append(
                      `${arKey}[${j}][${prop}]`,
                      new Date(arVal[j][prop])
                    )
                  } else {
                    formData.append(`${arKey}[${j}][${prop}]`, arVal[j][prop])
                  }
                }
              }
            }
          }
          continue // we don't need to append current element now, as its elements already appended
        } else {
          arVal = JSON.stringify(arVal)
        }
      }

      if (arVal === null) {
        continue
      }
      formData.append(arKey, arVal)
    }
    return formData
  };

  export const loadTypeform = async () => {
    // sorry I am not familiar with Vue and don't know how to load external scripts and styles :)
    return new Promise((resolve) => {
      const script = document.createElement("script");
      script.setAttribute('src', '//embed.typeform.com/next/embed.js')
      script.onload = resolve
      document.head.appendChild(script);
  
      const cssLink = document.createElement("link");
      cssLink.setAttribute('rel', 'stylesheet')
      cssLink.setAttribute('href', '//embed.typeform.com/next/css/slider.css')
      document.head.appendChild(cssLink);
    })
  }

  export const isAdmin = (role) => {
    if (!role) {
      const useAppStoreObj = useAppStore();
      useAppStoreObj.getComapnyAuth();
      role = useAppStoreObj.userRole;
    }
    return (role && (role.name === 'superadmin' || role.name === 'siteadmin'));
  };

  export const isMember = (role) => {
    if (!role) {
      const useAppStoreObj = useAppStore();
      useAppStoreObj.getComapnyAuth();
      role = useAppStoreObj.userRole;
    }
    return (role && role.name === 'employee');
  };
  export const isCompany = (role) => {
    if (!role) {
      const useAppStoreObj = useAppStore();
      useAppStoreObj.getComapnyAuth();
      role = useAppStoreObj.userRole;
    }
    return (role && (role.name === 'company' || role.name === 'businessadmin'));
  };

  export const isCompanyAdmin = (role) => {
    if (!role) {
      const useAppStoreObj = useAppStore();
      useAppStoreObj.getComapnyAuth();
      role = useAppStoreObj.userRole;
    }
    return (role && role.name === 'company');
  };

  export const isBusinessAdmin = (role) => {
    if (!role) {
      const useAppStoreObj = useAppStore();
      useAppStoreObj.getComapnyAuth();
      role = useAppStoreObj.userRole;
    }
    return (role &&  role.name === 'businessadmin');
  };
  
  export const validateEmail = (email) => {
    return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email));
  }