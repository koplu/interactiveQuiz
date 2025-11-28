let stage;
let groups = [];
let tmpGroup;

function calcWidth() {
    return (window.innerWidth > 600)
        ? document.getElementById("container").clientWidth
        : window.innerHeight;
}

function calcHeight() {
    return (window.innerWidth > 600)
        ? Math.max(300, window.innerHeight - 25)
        : window.innerWidth;
}

function rebuildStage() {
    if (stage) stage.destroy();
    buildAnswerStage();
}

function buildAnswerStage(){
    groups = [];

    answers = answers.filter(a => a.text.trim() !== "");

    for (let i = answers.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [answers[i], answers[j]] = [answers[j], answers[i]];
    }

    const containerEl = document.getElementById('container');
    const rows = Math.max(answers.length, 1);
    const stageWidth = containerEl.clientWidth;
    const fontSize = Math.max(16, Math.round(stageWidth * 0.05));
    const boxPadding = Math.round(fontSize * 0.5);
    const minTouchHeight = 48;
    const spacingFactor = 1.4;

    const rectHeights = answers.map(item => {
        const sampleText = new Konva.Text({
            fontSize: fontSize,
            fontFamily: 'Calibri',
            text: item.text,
            padding: boxPadding,
            width: stageWidth * 0.85,
            lineHeight: 1.2,
            wrap: 'word'
        });
        const textHeight = sampleText.height();
        return Math.max(minTouchHeight, textHeight + fontSize * 0.6);
    });

    const totalHeight = rectHeights.reduce((sum, h) => sum + h * spacingFactor, 0);
    const stageHeight = Math.max(300, totalHeight);
    containerEl.style.minHeight = stageHeight + 'px';

    stage = new Konva.Stage({
        container: 'container',
        width: stageWidth,
        height: stageHeight,
    });

    var layer = new Konva.Layer();
    stage.add(layer);

    let currentY = Math.max(20, Math.round((stageHeight - totalHeight) / 2));
    answers.forEach((item, index) => {
        const rectHeight = rectHeights[index];
        const rectWidth = Math.round(stageWidth * 0.85);

        const group = new Konva.Group({
            x: Math.round((stage.width() - rectWidth) / 2),
            y: currentY,
            draggable: true,
            name: "false"
        });

        const rect = new Konva.Rect({
            width: rectWidth,
            height: rectHeight,
            fill: "#aaf",
            cornerRadius: 8,
        });

        const text = new Konva.Text({
            fontSize: fontSize,
            fontFamily: 'Calibri',
            text: item.text,
            fill: 'black',
            padding: boxPadding,
            align: 'center',
            width: rectWidth,
            lineHeight: 1.2,
            wrap: 'word',
            listening: false,
        });

        text.x((rectWidth - text.width()) / 2);
        text.y((rectHeight - text.height()) / 2);

        group.add(rect);
        group.add(text);
        layer.add(group);
        groups.push(group);
        
        currentY += rectHeight + (rectHeight * (spacingFactor - 1));
    });

    layer.draw();

    var tmpGroup;

    for (let i = 0; i < groups.length; i++) {
        groups[i].on('pointerover', function () {
            document.body.style.cursor = 'pointer';
        });
        groups[i].on('pointerout', function () {
            document.body.style.cursor = 'default';
        });
        groups[i].on('dragstart', () => {
            groups[i].name = "true";
            tmpGroup = groups[i].y();
        })

        let collidedIndex = null;

        groups[i].on('dragmove', () => {
            const currentRect = groups[i].children[0];
            const centerX = Math.round((stage.width() - currentRect.width()) / 2);
            groups[i].x(centerX);

            if (groups[i].y() <= 0) groups[i].y(0);
            else if (groups[i].y() + groups[i].children[1].height() >= stage.height()) {
                groups[i].y(stage.height() - groups[i].children[1].height());
            }

            collidedIndex = null;
            for (let j = 0; j < groups.length; j++) {
                groups[j].children[0].fill("#aaf");
            }

            for (let j = 0; j < groups.length; j++) {
                if (j === i) continue;

                const aTop = groups[i].y();
                const aBottom = groups[i].y() + groups[i].children[1].height();
                const bTop = groups[j].y();
                const bBottom = groups[j].y() + groups[j].children[1].height();

                if (aBottom >= bTop && aTop <= bBottom) {
                    groups[j].children[0].fill("red");
                    collidedIndex = j;
                }
            }

            layer.batchDraw();
        });

        groups[i].on('dragend', () => {
            if (collidedIndex !== null) {
                const targetY = groups[collidedIndex].y();
                groups[collidedIndex].y(tmpGroup);
                groups[i].y(targetY);
            } else {
                groups[i].y(tmpGroup);
            }

            groups[i].name = "false";

            for (let j = 0; j < groups.length; j++) {
                groups[j].children[0].fill("#aaf");
            }

            layer.draw();
        });
    }
}

let lastOrientation = (window.innerWidth > window.innerHeight) ? "landscape" : "portrait"; 

function handleResize() {
    let currentOrientation = (window.innerWidth > window.innerHeight) ? "landscape" : "portrait";

    document.getElementById('rotate-overlay').style.display =
    (currentOrientation === "portrait") ? "none" : "flex";

    if (currentOrientation !== lastOrientation && currentOrientation === "portrait") {
        rebuildStage();
    }

    lastOrientation = currentOrientation;
}

window.addEventListener('resize', handleResize);
window.addEventListener('orientationchange', handleResize);

document.addEventListener("DOMContentLoaded", () => {
    handleResize();

    if (lastOrientation === "portrait") {
        rebuildStage();
    }
});

document.getElementsByClassName("next-btn")[0].addEventListener("click", function (e) {
    const ordered = groups
        .slice()
        .sort((a, b) => a.y() - b.y())
        .map((g, index) => ({
            text: g.children[1].text(),
            order: index + 1
    }));

    const padded = ordered.slice();
    while (padded.length < 4) {
        padded.push({ text: "", order: 0 });
    }

    document.getElementById("answerOrder").value = JSON.stringify(padded);
});