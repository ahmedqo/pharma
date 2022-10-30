const Translate = (function() {
            "use strict";

            var $OPTIONS = {},
                $TOGGLE;

            async function Translate(path, opts) {
                $OPTIONS = {
                    language: "en",
                    selector: "data-translate-id",
                    langBtn: false,
                    langList: {
                        en: "english",
                        fr: "french",
                        ...opts.langList,
                    },
                    path: path,
                    ...opts,
                };
                const items = document.querySelectorAll(`[${$OPTIONS.selector}]`);
                document.documentElement.lang = $OPTIONS.language;
                path = path + "-" + $OPTIONS.language + ".json";
                const data = await _fetch(path);
                items.forEach((item) => _set(item, data));
                localStorage.setItem("translate-language-id", $OPTIONS.language)
            }

            Translate.toggle = function(selector) {
                $TOGGLE = selector;
                const tar = document.querySelectorAll(selector);
                Array.from(tar).forEach((el) => {
                    if (el.style.display === "none") el.style.display = "";
                    else el.style.display = "none";
                });
            };

            Translate.run = function(event, selector) {
                const {
                    target
                } = event;
                const lang = target.dataset.lang;
                const iden = target.getAttribute(`${$OPTIONS.selector}`);
                const btn = document.querySelector(selector);
                btn.setAttribute($OPTIONS.selector, iden);
                this.toggle($TOGGLE);
                Translate($OPTIONS.path, {
                    ...$OPTIONS,
                    language: lang,
                });
            };

            async function _fetch(path) {
                const req = await fetch(path);
                const res = await req.json();
                return res;
            }

            function _check(obj, args) {
                var old = obj;
                for (var i = 0; i < args.length; i++) {
                    if (!old || !old.hasOwnProperty(args[i])) {
                        return null;
                    }
                    old = old[args[i]];
                }
                return eval(`obj${args.map((a) => `["${a}"]`).join("")};`);
    }

    function _set(el, data) {
        const id = el.getAttribute(`${$OPTIONS.selector}`);
        const ids = id.split(".").map((id) => `${id}`);
        const is = _check(data, ids);
        const tp = ["INPUT", "TEXTAREA"].includes(el.tagName);
        const fr = el.tagName === "IFRAME";
        if (is) fr ? el.srcdoc = is : el[tp ? "placeholder" : "innerHTML"] = is;
    }

    return Translate;
})();

window.addEventListener("DOMContentLoaded", () => {
    const LANG = localStorage.getItem("translate-language-id") || "fr";
    document.querySelectorAll("[data-lang]").forEach((btn) => {
        if (btn.dataset.lang === LANG)
            document.querySelector("#lang-btn").setAttribute("data-translate-id", btn.dataset.translateId)
    });
    Translate("/translate/data", {
        language: LANG
    });
});