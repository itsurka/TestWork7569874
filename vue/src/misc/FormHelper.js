import axios from "axios";
import contains from "@popperjs/core/lib/dom-utils/contains";

const FormHelper = {
    form: null,
    formElements: {},
    formSubmit: null,
    fieldNamePattern: null,
    setForm(form) {
        this.form = typeof form === 'object' ? form : document.getElementById(form);
        let that = this;
        this.form.elements.forEach(function(elem) {
            if (elem.nodeName === 'BUTTON') {
                if (elem.type === 'submit') {
                    that.formSubmit = elem;
                }
            } else {
                that.formElements[elem.name] = elem;
            }
        });
    },
    setFieldNamePattern(fieldNamePattern) {
        this.fieldNamePattern = fieldNamePattern;
    },
    beforeSubmit() {
        this.toggleDisableFormElements(true);
    },
    afterValidate(errors) {
        Array.from(this.form.getElementsByClassName('form-error')).forEach(
            function(elem, index, array) {
                elem.parentNode.removeChild(elem);
            }
        );
        this.setErrors(errors);
        this.toggleDisableFormElements(false);
    },
    toggleDisableFormElements(value) {
        for (let i in this.formElements) {
            let elem = this.formElements[i];
            if (value) {
                elem.setAttribute('disabled', 'disabled');
            } else {
                elem.removeAttribute('disabled');
            }
        }
        if (value) {
            this.formSubmit.setAttribute('disabled', 'disabled');
        } else {
            this.formSubmit.removeAttribute('disabled');
        }
    },
    setErrors(errors) {
        for (let field in errors) {
            let fieldErrors = errors[field];
            for (let i in fieldErrors) {
                this.fieldAddError(this.createFieldName(field), fieldErrors[i]);
            }
        }
    },
    fieldAddError(fieldName, error) {
        let field = this.formElements[fieldName];
        if (!field) {
            return;
        }
        let errorTag = document.createElement('p');
        errorTag.className = 'form-error text-danger';
        errorTag.innerText = error;
        field.after(errorTag);
    },
    createFieldName(errorKey) {
        if (!this.fieldNamePattern) {
            return errorKey;
        }
        return this.fieldNamePattern.replace('__NAME__', errorKey);
    },

    postRequest(url, data, successCallback, errorCallback, noResponseCallback, noRequestCallback) {
        this.makeRequest('post', url, data, successCallback, errorCallback, noResponseCallback, noRequestCallback);
    },

    makeRequest(method, url, data, successCallback, errorCallback, noResponseCallback, noRequestCallback) {
        axios[method](url, data).then(response => {
            if (successCallback) {
                successCallback(response);
            }
        }).catch(function (error) {
            if (error.response) {
                // Request made and server responded
                // console.log(error.response.data);
                // console.log(error.response.status);
                // console.log(error.response.headers);
                /*FormHelper.afterValidate({email: [error.response.data.error]});*/
                if (errorCallback) {
                    errorCallback(error.response);
                }
            } else if (error.request) {
                // The request was made but no response was received
                /*console.log(error.request);*/
                if (noRequestCallback) {
                    noResponseCallback(error.request);
                }
            } else {
                // Something happened in setting up the request that triggered an Error
                /*console.log('Error', error.message);*/
                if (noRequestCallback) {
                    noRequestCallback(error);
                }
            }

        });
    },

    submit(successCallback, errorCallback, noResponseCallback, noRequestCallback) {
        try {
            this.beforeSubmit();

            let method = this.form.getAttribute('method');
            if (!method) {
                throw 'Invalid method: ' + method;
            }
            let url = this.form.getAttribute('action');
            if (!url) {
                throw 'Url not found';
            }

            let data = this.formToJson(this.formElements);
            this.makeRequest(method, url, data, successCallback, errorCallback, noResponseCallback, noRequestCallback);
        } catch (error) {
            console.error('error', error);
        }
    },

    formToJson(formElements) {
        let data = {};
        for (let k in formElements) {
            let el = formElements[k];

            // get nested name
            let name = el.getAttribute('name');
            if (!name) {
                continue;
            }
            let re = /(\w+)?|\[(\w+)\]/g;
            let matches = name.match(re).filter(Boolean);

            if (typeof data[matches[0]] === 'undefined') {
                data[matches[0]] = {};
            }
            if (matches.length > 2) {
                throw 'Matches length is to high';
            } else if (matches.length === 2) {
                data[matches[0]][matches[1]] = el.value;
            } else {
                data[matches[0]] = el.value;
            }
        }
        return JSON.stringify(data);
    }
}

export default FormHelper;