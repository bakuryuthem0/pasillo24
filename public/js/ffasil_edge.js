/*jslint */
/*global AdobeEdge: false, window: false, document: false, console:false, alert: false */
(function (compId) {

    "use strict";
    var im='images/',
        aud='media/',
        vid='media/',
        js='js/',
        fonts = {
        },
        opts = {
            'gAudioPreloadPreference': 'auto',
            'gVideoPreloadPreference': 'auto'
        },
        resources = [
        ],
        scripts = [
        ],
        symbols = {
            "stage": {
                version: "5.0.1",
                minimumCompatibleVersion: "5.0.0",
                build: "5.0.1.386",
                scaleToFit: "none",
                centerStage: "both",
                resizeInstances: false,
                content: {
                    dom: [
                        {
                            id: 'Sin_ttulo-4-02',
                            type: 'image',
                            rect: ['225px', '82px', '70px', '27px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"Sin%20t%C3%ADtulo-4-02.png",'0px','0px']
                        },
                        {
                            id: 'Sin_ttulo-4-03',
                            type: 'image',
                            rect: ['190px', '-114px', '70px', '28px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"Sin%20t%C3%ADtulo-4-03.png",'0px','0px']
                        },
                        {
                            id: 'a1-01',
                            type: 'image',
                            rect: ['1017px', '6px', '236px', '162px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"a1-01.png",'0px','0px']
                        },
                        {
                            id: 'a2-01',
                            type: 'image',
                            rect: ['1070px', '164px', '25px', '25px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"a2-01.png",'0px','0px'],
                            transform: [[],['2900']]
                        },
                        {
                            id: 'a2-01Copy',
                            type: 'image',
                            rect: ['1158px', '146px', '25px', '25px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"a2-01.png",'0px','0px'],
                            transform: [[],['2900']]
                        },
                        {
                            id: 'a3-01',
                            type: 'image',
                            rect: ['1021px', '32px', '516px', '90px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"a3-01.png",'0px','0px']
                        },
                        {
                            id: 'a4-01',
                            type: 'image',
                            rect: ['351px', '128px', '487px', '25px', 'auto', 'auto'],
                            opacity: '0',
                            fill: ["rgba(0,0,0,0)",im+"a4-01.png",'0px','0px']
                        },
                        {
                            id: 'Sin_ttulo-4-01',
                            type: 'image',
                            rect: ['225px', '-45px', '35px', '27px', 'auto', 'auto'],
                            fill: ["rgba(0,0,0,0)",im+"Sin%20t%C3%ADtulo-4-01.png",'0px','0px']
                        }
                    ],
                    style: {
                        '${Stage}': {
                            isStage: true,
                            rect: ['null', 'null', '1000px', '200px', 'auto', 'auto'],
                            overflow: 'hidden',
                            fill: ["rgba(255,255,255,1)"]
                        }
                    }
                },
                timeline: {
                    duration: 19000,
                    autoPlay: true,
                    data: [
                        [
                            "eid32",
                            "left",
                            7000,
                            0,
                            "linear",
                            "${Sin_ttulo-4-01}",
                            '225px',
                            '225px'
                        ],
                        [
                            "eid34",
                            "top",
                            7000,
                            2750,
                            "easeOutBounce",
                            "${Sin_ttulo-4-01}",
                            '-45px',
                            '114px'
                        ],
                        [
                            "eid18",
                            "left",
                            0,
                            4000,
                            "linear",
                            "${a2-01}",
                            '1070px',
                            '218px'
                        ],
                        [
                            "eid38",
                            "left",
                            10000,
                            0,
                            "linear",
                            "${Sin_ttulo-4-03}",
                            '190px',
                            '190px'
                        ],
                        [
                            "eid61",
                            "rotateZ",
                            0,
                            4000,
                            "linear",
                            "${a2-01}",
                            '426deg',
                            '2900deg'
                        ],
                        [
                            "eid24",
                            "left",
                            4000,
                            3000,
                            "easeOutCubic",
                            "${a3-01}",
                            '1021px',
                            '330px'
                        ],
                        [
                            "eid36",
                            "left",
                            8000,
                            0,
                            "linear",
                            "${Sin_ttulo-4-02}",
                            '225px',
                            '225px'
                        ],
                        [
                            "eid19",
                            "left",
                            0,
                            4000,
                            "linear",
                            "${a1-01}",
                            '1017px',
                            '165px'
                        ],
                        [
                            "eid27",
                            "opacity",
                            12000,
                            7000,
                            "linear",
                            "${a4-01}",
                            '0.000000',
                            '1'
                        ],
                        [
                            "eid62",
                            "rotateZ",
                            0,
                            4000,
                            "linear",
                            "${a2-01Copy}",
                            '426deg',
                            '2900deg'
                        ],
                        [
                            "eid22",
                            "top",
                            4000,
                            0,
                            "linear",
                            "${a3-01}",
                            '32px',
                            '32px'
                        ],
                        [
                            "eid20",
                            "left",
                            0,
                            4000,
                            "linear",
                            "${a2-01Copy}",
                            '1158px',
                            '306px'
                        ],
                        [
                            "eid37",
                            "top",
                            8000,
                            4000,
                            "easeOutBounce",
                            "${Sin_ttulo-4-02}",
                            '-81px',
                            '82px'
                        ],
                        [
                            "eid43",
                            "top",
                            10000,
                            3000,
                            "easeOutBounce",
                            "${Sin_ttulo-4-03}",
                            '-114px',
                            '49px'
                        ]
                    ]
                }
            }
        };

    AdobeEdge.registerCompositionDefn(compId, symbols, fonts, scripts, resources, opts);

    if (!window.edge_authoring_mode) AdobeEdge.getComposition(compId).load("js/ffasil_edgeActions.js");
})("EDGE-22835156");
