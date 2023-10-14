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

/***/ "./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3.js":
/*!***********************************************************************************!*\
  !*** ./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3.js ***!
  \***********************************************************************************/
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
  function _defineProperty(obj, key, value) {
    if (key in obj) {
      Object.defineProperty(obj, key, {
        value: value,
        enumerable: true,
        configurable: true,
        writable: true
      });
    } else {
      obj[key] = value;
    }
    return obj;
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
  var t = FormValidation.utils.fetch;
  var s = /*#__PURE__*/function (_e) {
    _inherits(s, _e);
    var _super = _createSuper(s);
    function s(e) {
      var _this;
      _classCallCheck(this, s);
      _this = _super.call(this, e);
      _this.opts = Object.assign({}, {
        minimumScore: 0
      }, e);
      _this.iconPlacedHandler = _this.onIconPlaced.bind(_assertThisInitialized(_this));
      return _this;
    }
    _createClass(s, [{
      key: "install",
      value: function install() {
        var _this2 = this;
        this.core.on("plugins.icon.placed", this.iconPlacedHandler);
        var e = typeof window[s.LOADED_CALLBACK] === "undefined" ? function () {} : window[s.LOADED_CALLBACK];
        window[s.LOADED_CALLBACK] = function () {
          e();
          var i = document.createElement("input");
          i.setAttribute("type", "hidden");
          i.setAttribute("name", s.CAPTCHA_FIELD);
          document.getElementById(_this2.opts.element).appendChild(i);
          _this2.core.addField(s.CAPTCHA_FIELD, {
            validators: {
              promise: {
                message: _this2.opts.message,
                promise: function promise(e) {
                  return new Promise(function (e, i) {
                    window["grecaptcha"].execute(_this2.opts.siteKey, {
                      action: _this2.opts.action
                    }).then(function (o) {
                      t(_this2.opts.backendVerificationUrl, {
                        method: "POST",
                        params: _defineProperty({}, s.CAPTCHA_FIELD, o)
                      }).then(function (t) {
                        var _s = "".concat(t.success) === "true" && t.score >= _this2.opts.minimumScore;
                        e({
                          message: t.message || _this2.opts.message,
                          meta: t,
                          valid: _s
                        });
                      })["catch"](function (e) {
                        i({
                          valid: false
                        });
                      });
                    });
                  });
                }
              }
            }
          });
        };
        var i = this.getScript();
        if (!document.body.querySelector("script[src=\"".concat(i, "\"]"))) {
          var _e2 = document.createElement("script");
          _e2.type = "text/javascript";
          _e2.async = true;
          _e2.defer = true;
          _e2.src = i;
          document.body.appendChild(_e2);
        }
      }
    }, {
      key: "uninstall",
      value: function uninstall() {
        this.core.off("plugins.icon.placed", this.iconPlacedHandler);
        var e = this.getScript();
        var t = [].slice.call(document.body.querySelectorAll("script[src=\"".concat(e, "\"]")));
        t.forEach(function (e) {
          return e.parentNode.removeChild(e);
        });
        this.core.removeField(s.CAPTCHA_FIELD);
      }
    }, {
      key: "getScript",
      value: function getScript() {
        var e = this.opts.language ? "&hl=".concat(this.opts.language) : "";
        return "https://www.google.com/recaptcha/api.js?" + "onload=".concat(s.LOADED_CALLBACK, "&render=").concat(this.opts.siteKey).concat(e);
      }
    }, {
      key: "onIconPlaced",
      value: function onIconPlaced(e) {
        if (e.field === s.CAPTCHA_FIELD) {
          e.iconElement.style.display = "none";
        }
      }
    }]);
    return s;
  }(e);
  s.CAPTCHA_FIELD = "___g-recaptcha-token___";
  s.LOADED_CALLBACK = "___reCaptcha3Loaded___";
  return s;
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
/******/ 	var __webpack_exports__ = __webpack_require__("./resources/assets/vendor/libs/formvalidation/dist/js/plugins/Recaptcha3.js");
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});