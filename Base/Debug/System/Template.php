<?php

namespace Dotsquares\Base\Debug\System;

/**
 * @codeCoverageIgnore
 * @codingStandardsIgnoreFile
 */
class Template
{
    public static $varWrapper = '<div class="dotsquares-base-debug-wrapper"><code>%s</code></div>';

    public static $string = '"<span class="dotsquares-base-string">%s</span>"';

    public static $var = '<span class="dotsquares-base-var">%s</span>';

    public static $arrowsOpened =  '<span class="dotsquares-base-arrow" data-opened="true">&#x25BC;</span>
        <div class="dotsquares-base-array">';

    public static $arrowsClosed = '<span class="dotsquares-base-arrow" data-opened="false">&#x25C0;</span>
        <div class="dotsquares-base-array dotsquares-base-hidden">';

    public static $arrayHeader = '<span class="dotsquares-base-info">array:%s</span> [';

    public static $array = '<div class="dotsquares-base-array-line" style="padding-left:%s0px">
            %s  => %s
        </div>';

    public static $arrayFooter = '</div>]';

    public static $arrayKeyString = '"<span class="dotsquares-base-array-key">%s</span>"';

    public static $arrayKey = '<span class="dotsquares-base-array-key">%s</span>';

    public static $arraySimpleVar = '<span class="dotsquares-base-array-value">%s</span>';

    public static $arraySimpleString = '"<span class="dotsquares-base-array-string-value">%s</span>"';

    public static $objectHeader = '<span class="dotsquares-base-info" title="%s">Object: %s</span> {';

    public static $objectMethod = '<div class="dotsquares-base-object-method-line" style="padding-left:%s0px">
            #%s
        </div>';

    public static $objectMethodHeader = '<span style="margin-left:%s0px">Methods: </span>
        <span class="dotsquares-base-arrow" data-opened="false">◀</span>
        <div class="dotsquares-base-array  dotsquares-base-hidden">';

    public static $objectMethodFooter = '</div>';

    public static $objectFooter = '</div> }';

    public static $debugJsCss = '<script>
            var dotsquaresToggle = function() {
                if (this.dataset.opened == "true") {
                    this.innerHTML = "&#x25C0";
                    this.dataset.opened = "false";
                    this.nextElementSibling.className = "dotsquares-base-array dotsquares-base-hidden";
                } else {
                    this.innerHTML = "&#x25BC;";
                    this.dataset.opened = "true";
                    this.nextElementSibling.className = "dotsquares-base-array";
                }
            };
            document.addEventListener("DOMContentLoaded", function() {
                arrows = document.getElementsByClassName("dotsquares-base-arrow");
                for (i = 0; i < arrows.length; i++) {
                    arrows[i].addEventListener("click", dotsquaresToggle,false);
                }
            });
        </script>
        <style>
            .dotsquares-base-debug-wrapper {
                background-color: #263238;
                color: #ff9416;
                font-size: 13px;
                padding: 10px;
                border-radius: 3px;
                z-index: 1000000;
                margin: 20px 0;
            }
            .dotsquares-base-debug-wrapper code {
                background: transparent !important;
                color: inherit !important;
                padding: 0;
                font-size: inherit;
                white-space: inherit;
            }
            .dotsquares-base-info {
                color: #82AAFF;
            }
            .dotsquares-base-var, .dotsquares-base-array-key {
                color: #fff;
            }
            .dotsquares-base-array-value {
                color: #C792EA;
                font-weight: bold;
            }
            .dotsquares-base-arrow {
                cursor: pointer;
                color: #82aaff;
            }
            .dotsquares-base-hidden {
                display:none;
            }
            .dotsquares-base-string, .dotsquares-base-array-string-value {
                font-weight: bold;
                color: #c3e88d;
            }
            .dotsquares-base-object-method-line {
                color: #fff;
            }
        </style>';
}
