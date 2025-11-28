let stage;
let lineList = [];
let groupList1 = [];
let groupList2 = [];

function calcWidth() {
    return (window.innerWidth > 600)
        ? document.getElementById("matchAnswer").clientWidth
        : window.innerHeight;
}

function calcHeight() {
    return (window.innerWidth > 600)
        ? Math.max(300, window.innerHeight - 25)
        : window.innerWidth;
}

function rebuildStage() {
    if (stage) stage.destroy();

    stage = new Konva.Stage({
        container: "matchAnswer",
        width: calcWidth(),
        height: calcHeight()
    });

    buildStage();
}

function buildStage(){

    lineList = [];
    groupList1 = [];
    groupList2 = [];

    pairs = pairs
        .map(p => ({ left: p.left, right: p.right }))
        .sort(() => Math.random() - 0.5);

    let rightShuffled = pairs
        .map(p => p.right)
        .sort(() => Math.random() - 0.5);

    var layer = new Konva.Layer();
    stage.add(layer);

    var textList1 = [];
    var textList2 = [];
    var rectList1 = [];
    var rectList2 = [];
 
    pairs.forEach((p) => {
        textList1.push(new Konva.Text({
            fontSize: 26,
            fontFamily: 'Calibri',
            text: p.left,
            fill: 'black',
            padding: 10,
        }));
    });

    rightShuffled.forEach((right) => {
        textList2.push(new Konva.Text({
            fontSize: 26,
            fontFamily: 'Calibri',
            text: right,
            fill: 'black',
            padding: 10,
        }));
    });

    function setAnim(group){
        group.on('pointerover', function () {
            document.body.style.cursor = 'pointer';
        });
        group.on('pointerout', function () {
            document.body.style.cursor = 'default';
        });
    }

    for (let i = 0; i < textList1.length; i++) {
        rectList1.push(new Konva.Rect({
            width: textList1[i].width(),
            height: textList1[i].height(),
            fill: '#aaf',
            cornerRadius: 8
        }));
    }
    for (let i = 0; i < rectList1.length; i++) {
        groupList1.push(new Konva.Group({
            x: 30,
            y: 50 + (textList1[i].height() + 16) * i,
            width: textList1[i].width(),
            height: textList1[i].height(),
        }));
        groupList1[i].add(rectList1[i]).add(textList1[i]);
        setAnim(groupList1[i]);
    }
    for (let i = 0; i < groupList1.length; i++) {
        layer.add(groupList1[i]);
    }

    for (let i = 0; i < textList2.length; i++) {
        rectList2.push(new Konva.Rect({
            width: textList2[i].width(),
            height: textList2[i].height(),
            fill: '#aaf',
            cornerRadius: 8
        }));
    }
    for (let i = 0; i < rectList2.length; i++) {
        groupList2.push(new Konva.Group({
            x: stage.width() - textList2[i].width() - 30,
            y: 50 + (textList2[i].height() + 16) * i,
            width: textList2[i].width(),
            height: textList2[i].height(),
        }));
        groupList2[i].add(rectList2[i]).add(textList2[i]);
        setAnim(groupList2[i]);
    }
    for (let i = 0; i < groupList2.length; i++) {
        layer.add(groupList2[i]);
    }

    var isLine = false;
    var lastLine;
    var posX, posY;
    var pos2X, pos2Y;
    var snapPos2X = null, snapPos2Y = null;
    var startGroup = null;
    var groupStart, groupEnds;

    stage.on('mousedown touchstart', function (e) {

        let pos = stage.getPointerPosition();
        for (let i = 0; i < groupList1.length; i++) {
            if (pos.x >= groupList1[i].x() && pos.x <= groupList1[i].x() + groupList1[i].width() &&
                pos.y >= groupList1[i].y() && pos.y <= groupList1[i].y() + groupList1[i].height()){

                isLine = true;

                if (lineList.length > 0){
                    for (let j = 0; j < lineList.length; j++) {
                        if (lineList[j].attrs.groupStart === groupList1[i]){
                            lineList[j].remove();
                            lineList.splice(j,1);
                            break;
                        }
                        else if (lineList[j].attrs.groupEnds === groupList1[i]){
                            lineList[j].remove();
                            lineList.splice(j,1);
                            break;
                        }
                    }
                }

                posX = groupList1[i].x() + groupList1[i].width()/2;
                posY = groupList1[i].y() + groupList1[i].height()/2;

                startGroup = "first";
                groupStart = groupList1[i];

                break;
            }
        }
        for (let i = 0; i < groupList2.length; i++) {
            if (pos.x >= groupList2[i].x() && pos.x <= groupList2[i].x() + groupList2[i].width() &&
                pos.y >= groupList2[i].y() && pos.y <= groupList2[i].y() + groupList2[i].height()){

                isLine = true;

                if (lineList.length > 0){
                    for (let j = 0; j < lineList.length; j++) {
                        if (lineList[j].attrs.groupStart === groupList2[i]){
                            lineList[j].remove();
                            lineList.splice(j,1);
                            break;
                        }
                        else if (lineList[j].attrs.groupEnds === groupList2[i]){
                            lineList[j].remove();
                            lineList.splice(j,1);
                            break;
                        }
                    }
                }

                posX = groupList2[i].x() + groupList2[i].width()/2;
                posY = groupList2[i].y() + groupList2[i].height()/2;

                startGroup = "second";
                groupStart = groupList2[i];

                break;
            }
        }
    });

    stage.on('mousemove touchmove', function (e) {

        if (!isLine) {
            return;
        }
        var pos2 = stage.getPointerPosition();
        pos2X = pos2.x;
        pos2Y = pos2.y;

        if(lastLine != null){
            lastLine.destroy();
        }

        lastLine = new Konva.Line({
            stroke: '#5e5e8b',
            strokeWidth: 5,
            lineCap: 'round',
            lineJoin: 'round',
            points: [posX, posY, pos2.x, pos2.y],
        });

        e.evt.preventDefault();

        if (startGroup === "second"){
            for (let i = 0; i < groupList1.length; i++) {
                if(pos2.x <= groupList1[i].x() + groupList1[i].width() && pos2.x >= groupList1[i].x() &&
                    pos2.y <= groupList1[i].y() + groupList1[i].height() && pos2.y >= groupList1[i].y()){

                    lastLine.points([posX, posY, groupList1[i].x() + groupList1[i].width()/2, groupList1[i].y() + groupList1[i].height()/2]);
                    snapPos2X = groupList1[i].x() + groupList1[i].width()/2;
                    snapPos2Y = groupList1[i].y() + groupList1[i].height()/2;
                    groupEnds = groupList1[i];

                    break;
                }
                else{
                    snapPos2X = null; snapPos2Y = null;
                }
            }
        }

        if (startGroup === "first"){
            for (let i = 0; i < groupList2.length; i++) {
                if(pos2.x <= groupList2[i].x() + groupList2[i].width() && pos2.x >= groupList2[i].x() &&
                    pos2.y <= groupList2[i].y() + groupList2[i].height() && pos2.y >= groupList2[i].y()){

                    lastLine.points([posX, posY, groupList2[i].x() + groupList2[i].width()/2, groupList2[i].y() + groupList2[i].height()/2]);
                    snapPos2X = groupList2[i].x() + groupList2[i].width()/2;
                    snapPos2Y = groupList2[i].y() + groupList2[i].height()/2;
                    groupEnds = groupList2[i];

                    break;
                }
                else{
                    snapPos2X = null; snapPos2Y = null;
                }
            }
        }

        layer.add(lastLine);
        lastLine.moveToBottom();
    });

    stage.on('mouseup touchend', function () {
        isLine = false;

        let line;
        if (snapPos2X == null && snapPos2Y == null){
            if (lastLine){
                lastLine.destroy();
            }
        }

        else if (snapPos2X !== null && snapPos2Y !== null){

            if (lineList.length > 0){
                for (let i = 0; i < lineList.length; i++) {
                    if (groupEnds === lineList[i].attrs.groupStart ||
                        groupEnds === lineList[i].attrs.groupEnds){
                        lineList[i].remove();
                        lineList.splice(i,1);
                    }
                }
            }

            line = new Konva.Line({
                stroke: '#5e5e8b',
                strokeWidth: 5,
                lineCap: 'round',
                lineJoin: 'round',
                points: [posX, posY, snapPos2X, snapPos2Y],
                groupStart: groupStart,
                groupEnds: groupEnds,
            });
            lineList.push(line);
        }

        if (lineList.length > 0){
            for (let i = 0; i < lineList.length; i++) {
                layer.add(lineList[i]);
                lineList[i].moveToBottom();
            }
        }

        snapPos2X = null;
        snapPos2Y = null;
        groupStart = null;
        groupEnds = null;
    });
}

document.getElementsByClassName("next-btn")[0].addEventListener("click", function (e) {
    let userMatches = [];

    lineList.forEach(line => {
        let start = line.attrs.groupStart;
        let end = line.attrs.groupEnds;

        if (!start || !end) return;

        let leftGroup, rightGroup;

        if (groupList1.includes(start)) {
            leftGroup = start;
            rightGroup = end;
        } else {
            leftGroup = end;
            rightGroup = start;
        }

        let leftText = leftGroup.getChildren()[1].text();
        let rightText = rightGroup.getChildren()[1].text();

        userMatches.push({
            left: leftText,
            right: rightText
        });
    });

    document.getElementById("matches").value = JSON.stringify(userMatches);
});