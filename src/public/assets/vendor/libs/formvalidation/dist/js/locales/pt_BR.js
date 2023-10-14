(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else {
		var a = factory();
		for(var i in a) (typeof exports === 'object' ? exports : root)[i] = a[i];
	}
})(self, function() {
return /******/ (function() { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/assets/vendor/libs/formvalidation/dist/js/locales/pt_BR.js":
/*!******************************************************************************!*\
  !*** ./resources/assets/vendor/libs/formvalidation/dist/js/locales/pt_BR.js ***!
  \******************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
(function (global, factory) {
  ( false ? 0 : _typeof(exports)) === 'object' && "object" !== 'undefined' ? module.exports = factory() :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : (0);
})(this, function () {
  'use strict';

  /**
   * Portuguese (Brazil) language package
   * Translated by @marcuscarvalho6. Improved by @dgmike
   */
  var pt_BR = {
    base64: {
      "default": 'Por favor insira um código base 64 válido'
    },
    between: {
      "default": 'Por favor insira um valor entre %s e %s',
      notInclusive: 'Por favor insira um valor estritamente entre %s e %s'
    },
    bic: {
      "default": 'Por favor insira um número BIC válido'
    },
    callback: {
      "default": 'Por favor insira um valor válido'
    },
    choice: {
      between: 'Por favor escolha de %s a %s opções',
      "default": 'Por favor insira um valor válido',
      less: 'Por favor escolha %s opções no mínimo',
      more: 'Por favor escolha %s opções no máximo'
    },
    color: {
      "default": 'Por favor insira uma cor válida'
    },
    creditCard: {
      "default": 'Por favor insira um número de cartão de crédito válido'
    },
    cusip: {
      "default": 'Por favor insira um número CUSIP válido'
    },
    date: {
      "default": 'Por favor insira uma data válida',
      max: 'Por favor insira uma data anterior a %s',
      min: 'Por favor insira uma data posterior a %s',
      range: 'Por favor insira uma data entre %s e %s'
    },
    different: {
      "default": 'Por favor insira valores diferentes'
    },
    digits: {
      "default": 'Por favor insira somente dígitos'
    },
    ean: {
      "default": 'Por favor insira um número EAN válido'
    },
    ein: {
      "default": 'Por favor insira um número EIN válido'
    },
    emailAddress: {
      "default": 'Por favor insira um email válido'
    },
    file: {
      "default": 'Por favor escolha um arquivo válido'
    },
    greaterThan: {
      "default": 'Por favor insira um valor maior ou igual a %s',
      notInclusive: 'Por favor insira um valor maior do que %s'
    },
    grid: {
      "default": 'Por favor insira uma GRID válida'
    },
    hex: {
      "default": 'Por favor insira um hexadecimal válido'
    },
    iban: {
      countries: {
        AD: 'Andorra',
        AE: 'Emirados Árabes',
        AL: 'Albânia',
        AO: 'Angola',
        AT: 'Áustria',
        AZ: 'Azerbaijão',
        BA: 'Bósnia-Herzegovina',
        BE: 'Bélgica',
        BF: 'Burkina Faso',
        BG: 'Bulgária',
        BH: 'Bahrain',
        BI: 'Burundi',
        BJ: 'Benin',
        BR: 'Brasil',
        CH: 'Suíça',
        CM: 'Camarões',
        CR: 'Costa Rica',
        CV: 'Cabo Verde',
        CY: 'Chipre',
        CZ: 'República Checa',
        DE: 'Alemanha',
        DK: 'Dinamarca',
        DO: 'República Dominicana',
        DZ: 'Argélia',
        EE: 'Estónia',
        ES: 'Espanha',
        FI: 'Finlândia',
        FO: 'Ilhas Faroé',
        FR: 'França',
        GB: 'Reino Unido',
        GE: 'Georgia',
        GI: 'Gibraltar',
        GL: 'Groenlândia',
        GR: 'Grécia',
        GT: 'Guatemala',
        HR: 'Croácia',
        HU: 'Hungria',
        IC: 'Costa do Marfim',
        IE: 'Ireland',
        IL: 'Israel',
        IR: 'Irão',
        IS: 'Islândia',
        JO: 'Jordan',
        KW: 'Kuwait',
        KZ: 'Cazaquistão',
        LB: 'Líbano',
        LI: 'Liechtenstein',
        LT: 'Lituânia',
        LU: 'Luxemburgo',
        LV: 'Letónia',
        MC: 'Mônaco',
        MD: 'Moldávia',
        ME: 'Montenegro',
        MG: 'Madagascar',
        MK: 'Macedónia',
        ML: 'Mali',
        MR: 'Mauritânia',
        MT: 'Malta',
        MU: 'Maurício',
        MZ: 'Moçambique',
        NL: 'Países Baixos',
        NO: 'Noruega',
        PK: 'Paquistão',
        PL: 'Polônia',
        PS: 'Palestino',
        PT: 'Portugal',
        QA: 'Qatar',
        RO: 'Roménia',
        RS: 'Sérvia',
        SA: 'Arábia Saudita',
        SE: 'Suécia',
        SI: 'Eslovénia',
        SK: 'Eslováquia',
        SM: 'San Marino',
        SN: 'Senegal',
        TI: 'Itália',
        TL: 'Timor Leste',
        TN: 'Tunísia',
        TR: 'Turquia',
        VG: 'Ilhas Virgens Britânicas',
        XK: 'República do Kosovo'
      },
      country: 'Por favor insira um número IBAN válido em %s',
      "default": 'Por favor insira um número IBAN válido'
    },
    id: {
      countries: {
        BA: 'Bósnia e Herzegovina',
        BG: 'Bulgária',
        BR: 'Brasil',
        CH: 'Suíça',
        CL: 'Chile',
        CN: 'China',
        CZ: 'República Checa',
        DK: 'Dinamarca',
        EE: 'Estônia',
        ES: 'Espanha',
        FI: 'Finlândia',
        HR: 'Croácia',
        IE: 'Irlanda',
        IS: 'Islândia',
        LT: 'Lituânia',
        LV: 'Letónia',
        ME: 'Montenegro',
        MK: 'Macedónia',
        NL: 'Holanda',
        PL: 'Polônia',
        RO: 'Roménia',
        RS: 'Sérvia',
        SE: 'Suécia',
        SI: 'Eslovênia',
        SK: 'Eslováquia',
        SM: 'San Marino',
        TH: 'Tailândia',
        TR: 'Turquia',
        ZA: 'África do Sul'
      },
      country: 'Por favor insira um número de indentificação válido em %s',
      "default": 'Por favor insira um código de identificação válido'
    },
    identical: {
      "default": 'Por favor, insira o mesmo valor'
    },
    imei: {
      "default": 'Por favor insira um IMEI válido'
    },
    imo: {
      "default": 'Por favor insira um IMO válido'
    },
    integer: {
      "default": 'Por favor insira um número inteiro válido'
    },
    ip: {
      "default": 'Por favor insira um IP válido',
      ipv4: 'Por favor insira um endereço de IPv4 válido',
      ipv6: 'Por favor insira um endereço de IPv6 válido'
    },
    isbn: {
      "default": 'Por favor insira um ISBN válido'
    },
    isin: {
      "default": 'Por favor insira um ISIN válido'
    },
    ismn: {
      "default": 'Por favor insira um ISMN válido'
    },
    issn: {
      "default": 'Por favor insira um ISSN válido'
    },
    lessThan: {
      "default": 'Por favor insira um valor menor ou igual a %s',
      notInclusive: 'Por favor insira um valor menor do que %s'
    },
    mac: {
      "default": 'Por favor insira um endereço MAC válido'
    },
    meid: {
      "default": 'Por favor insira um MEID válido'
    },
    notEmpty: {
      "default": 'Por favor insira um valor'
    },
    numeric: {
      "default": 'Por favor insira um número real válido'
    },
    phone: {
      countries: {
        AE: 'Emirados Árabes',
        BG: 'Bulgária',
        BR: 'Brasil',
        CN: 'China',
        CZ: 'República Checa',
        DE: 'Alemanha',
        DK: 'Dinamarca',
        ES: 'Espanha',
        FR: 'França',
        GB: 'Reino Unido',
        IN: 'Índia',
        MA: 'Marrocos',
        NL: 'Países Baixos',
        PK: 'Paquistão',
        RO: 'Roménia',
        RU: 'Rússia',
        SK: 'Eslováquia',
        TH: 'Tailândia',
        US: 'EUA',
        VE: 'Venezuela'
      },
      country: 'Por favor insira um número de telefone válido em %s',
      "default": 'Por favor insira um número de telefone válido'
    },
    promise: {
      "default": 'Por favor insira um valor válido'
    },
    regexp: {
      "default": 'Por favor insira um valor correspondente ao padrão'
    },
    remote: {
      "default": 'Por favor insira um valor válido'
    },
    rtn: {
      "default": 'Por favor insira um número válido RTN'
    },
    sedol: {
      "default": 'Por favor insira um número válido SEDOL'
    },
    siren: {
      "default": 'Por favor insira um número válido SIREN'
    },
    siret: {
      "default": 'Por favor insira um número válido SIRET'
    },
    step: {
      "default": 'Por favor insira um passo válido %s'
    },
    stringCase: {
      "default": 'Por favor, digite apenas caracteres minúsculos',
      upper: 'Por favor, digite apenas caracteres maiúsculos'
    },
    stringLength: {
      between: 'Por favor insira um valor entre %s e %s caracteres',
      "default": 'Por favor insira um valor com comprimento válido',
      less: 'Por favor insira menos de %s caracteres',
      more: 'Por favor insira mais de %s caracteres'
    },
    uri: {
      "default": 'Por favor insira um URI válido'
    },
    uuid: {
      "default": 'Por favor insira um número válido UUID',
      version: 'Por favor insira uma versão %s  UUID válida'
    },
    vat: {
      countries: {
        AT: 'Áustria',
        BE: 'Bélgica',
        BG: 'Bulgária',
        BR: 'Brasil',
        CH: 'Suíça',
        CY: 'Chipre',
        CZ: 'República Checa',
        DE: 'Alemanha',
        DK: 'Dinamarca',
        EE: 'Estônia',
        EL: 'Grécia',
        ES: 'Espanha',
        FI: 'Finlândia',
        FR: 'França',
        GB: 'Reino Unido',
        GR: 'Grécia',
        HR: 'Croácia',
        HU: 'Hungria',
        IE: 'Irlanda',
        IS: 'Islândia',
        IT: 'Itália',
        LT: 'Lituânia',
        LU: 'Luxemburgo',
        LV: 'Letónia',
        MT: 'Malta',
        NL: 'Holanda',
        NO: 'Norway',
        PL: 'Polônia',
        PT: 'Portugal',
        RO: 'Roménia',
        RS: 'Sérvia',
        RU: 'Rússia',
        SE: 'Suécia',
        SI: 'Eslovênia',
        SK: 'Eslováquia',
        VE: 'Venezuela',
        ZA: 'África do Sul'
      },
      country: 'Por favor insira um número VAT válido em %s',
      "default": 'Por favor insira um VAT válido'
    },
    vin: {
      "default": 'Por favor insira um VIN válido'
    },
    zipCode: {
      countries: {
        AT: 'Áustria',
        BG: 'Bulgária',
        BR: 'Brasil',
        CA: 'Canadá',
        CH: 'Suíça',
        CZ: 'República Checa',
        DE: 'Alemanha',
        DK: 'Dinamarca',
        ES: 'Espanha',
        FR: 'França',
        GB: 'Reino Unido',
        IE: 'Irlanda',
        IN: 'Índia',
        IT: 'Itália',
        MA: 'Marrocos',
        NL: 'Holanda',
        PL: 'Polônia',
        PT: 'Portugal',
        RO: 'Roménia',
        RU: 'Rússia',
        SE: 'Suécia',
        SG: 'Cingapura',
        SK: 'Eslováquia',
        US: 'EUA'
      },
      country: 'Por favor insira um código postal válido em %s',
      "default": 'Por favor insira um código postal válido'
    }
  };
  return pt_BR;
});

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module is referenced by other modules so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/assets/vendor/libs/formvalidation/dist/js/locales/pt_BR.js");
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});