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

/***/ "./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3Token.js":
/*!****************************************************************************************!*\
  !*** ./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3Token.js ***!
  \****************************************************************************************/
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
/**
 * FormValidation (https://formvalidation.io), v1.10.0 (2236098)
 * The best validation library for JavaScript
 * (c) 2013 - 2021 Nguyen Huu Phuoc <me@phuoc.ng>
 */

(function (global, factory) {
  ( false ? 0 : _typeof(exports)) === 'object' && "object" !== 'undefined' ? module.exports = factory() :  true ? !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
		__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
		(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
		__WEBPACK_AMD_DEFINE_FACTORY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : (0);
})(this, function () {
  'use strict';

  function _classCallCheck(instance, Constructor) {
    if (!(instance instanceof Constructor)) {
      throw new TypeError("Cannot call a class as a function");
    }
  }
  function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      Object.defineProperty(target, descriptor.key, descriptor);
    }
  }
  function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    Object.defineProperty(Constructor, "prototype", {
      writable: false
    });
    return Constructor;
  }
  function _inherits(subClass, superClass) {
    if (typeof superClass !== "function" && superClass !== null) {
      throw new TypeError("Super expression must either be null or a function");
    }
    subClass.prototype = Object.create(superClass && superClass.prototype, {
      constructor: {
        value: subClass,
        writable: true,
        configurable: true
      }
    });
    Object.defineProperty(subClass, "prototype", {
      writable: false
    });
    if (superClass) _setPrototypeOf(subClass, superClass);
  }
  function _getPrototypeOf(o) {
    _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf.bind() : function _getPrototypeOf(o) {
      return o.__proto__ || Object.getPrototypeOf(o);
    };
    return _getPrototypeOf(o);
  }
  function _setPrototypeOf(o, p) {
    _setPrototypeOf = Object.setPrototypeOf ? Object.setPrototypeOf.bind() : function _setPrototypeOf(o, p) {
      o.__proto__ = p;
      return o;
    };
    return _setPrototypeOf(o, p);
  }
  function _isNativeReflectConstruct() {
    if (typeof Reflect === "undefined" || !Reflect.construct) return false;
    if (Reflect.construct.sham) return false;
    if (typeof Proxy === "function") return true;
    try {
      Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {}));
      return true;
    } catch (e) {
      return false;
    }
  }
  function _assertThisInitialized(self) {
    if (self === void 0) {
      throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
    }
    return self;
  }
  function _possibleConstructorReturn(self, call) {
    if (call && (_typeof(call) === "object" || typeof call === "function")) {
      return call;
    } else if (call !== void 0) {
      throw new TypeError("Derived constructors may only return object or undefined");
    }
    return _assertThisInitialized(self);
  }
  function _createSuper(Derived) {
    var hasNativeReflectConstruct = _isNativeReflectConstruct();
    return function _createSuperInternal() {
      var Super = _getPrototypeOf(Derived),
        result;
      if (hasNativeReflectConstruct) {
        var NewTarget = _getPrototypeOf(this).constructor;
        result = Reflect.construct(Super, arguments, NewTarget);
      } else {
        result = Super.apply(this, arguments);
      }
      return _possibleConstructorReturn(this, result);
    };
  }
  var e = FormValidation.Plugin;
  var t = /*#__PURE__*/function (_e) {
    _inherits(t, _e);
    var _super = _createSuper(t);
    function t(e) {
      var _this;
      _classCallCheck(this, t);
      _this = _super.call(this, e);
      _this.opts = Object.assign({}, {
        action: "submit",
        hiddenTokenName: "___hidden-token___"
      }, e);
      _this.onValidHandler = _this.onFormValid.bind(_assertThisInitialized(_this));
      return _this;
    }
    _createClass(t, [{
      key: "install",
      value: function install() {
        this.core.on("core.form.valid", this.onValidHandler);
        this.hiddenTokenEle = document.createElement("input");
        this.hiddenTokenEle.setAttribute("type", "hidden");
        this.core.getFormElement().appendChild(this.hiddenTokenEle);
        var e = typeof window[t.LOADED_CALLBACK] === "undefined" ? function () {} : window[t.LOADED_CALLBACK];
        window[t.LOADED_CALLBACK] = function () {
          e();
        };
        var o = this.getScript();
        if (!document.body.querySelector("script[src=\"".concat(o, "\"]"))) {
          var _e2 = document.createElement("script");
          _e2.type = "text/javascript";
          _e2.async = true;
          _e2.defer = true;
          _e2.src = o;
          document.body.appendChild(_e2);
        }
      }
    }, {
      key: "uninstall",
      value: function uninstall() {
        this.core.off("core.form.valid", this.onValidHandler);
        var e = this.getScript();
        var _t = [].slice.call(document.body.querySelectorAll("script[src=\"".concat(e, "\"]")));
        _t.forEach(function (e) {
          return e.parentNode.removeChild(e);
        });
        this.core.getFormElement().removeChild(this.hiddenTokenEle);
      }
    }, {
      key: "onFormValid",
      value: function onFormValid() {
        var _this2 = this;
        window["grecaptcha"].execute(this.opts.siteKey, {
          action: this.opts.action
        }).then(function (e) {
          _this2.hiddenTokenEle.setAttribute("name", _this2.opts.hiddenTokenName);
          _this2.hiddenTokenEle.value = e;
          var _t2 = _this2.core.getFormElement();
          if (_t2 instanceof HTMLFormElement) {
            _t2.submit();
          }
        });
      }
    }, {
      key: "getScript",
      value: function getScript() {
        var e = this.opts.language ? "&hl=".concat(this.opts.language) : "";
        return "https://www.google.com/recaptcha/api.js?" + "onload=".concat(t.LOADED_CALLBACK, "&render=").concat(this.opts.siteKey).concat(e);
      }
    }]);
    return t;
  }(e);
  t.LOADED_CALLBACK = "___reCaptcha3Loaded___";
  return t;
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
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3Token.js");
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});