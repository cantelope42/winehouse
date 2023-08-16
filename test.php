<!DOCTYPE html>
<html>
  <head>
    <style>
      body,html{
        background: linear-gradient(45deg,#333,#000);
        margin: 0;
        height: 100vh;
        overflow: hidden;
        font-family: courier;
        font-size: 16px;
        color: #ffa;
      }
      .main{
        margin: 20px;
        text-align: center;
      }
      .linkInputContainer{
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translate(-50%, -50%);
      }
      button{
        background: #264;
        color: #fff;
        border: none;
				font-family: courier;
				border-radius: 10px;
				width: 200px;
				font-size: 16px;
        z-index: 100;
        cursor: pointer;
        display: block;
        margin: 10px;
        padding: 4px;
      }
      #playButton{
        background: #333;
        color: #888;
        border: none;
				font-family: courier;
				border-radius: 10px;
				width: 100px;
				font-size: 24px;
        display: none;
        z-index: 100;
        cursor: pointer;
      }
      #targetInput{
        background: #000;
        border: none;
        outline: none;
        color: #cfc;
        display: none;
        width: 400px;
        text-align: center;
        border-bottom: 1px solid #4fc8;
        font-size: 14px;
        font-family: courier;
      }
      #c{
        position: absolute;
        top: 50%;
        left: 50%;
        width: 100%;
        height: 100%;
        background-position: center center;
        background-size: fill;
        transform: translate(-50%, -50%);
      }
      video{
        display: none;
      }
      .validStatus{
        position: absolute;
        left: 50%;
        margin-top: -20px;
        transform: translate(-50%);
      }
    </style>
  </head>
  <body>
    <div class="main">
      <canvas id=c></canvas>
        <div class="linkInputContainer">
          choose a live feed...
          <button onclick="launchFeed(0)">addams family</button>
          <button onclick="launchFeed(1)">aquarium</button>
          <button onclick="launchFeed(2)">earth from space</button>
          <button onclick="launchFeed(3)">jellyfish</button>
          <input
            oninput="validate()"
            onkeydown="playMaybe(event)"
            autofocus
            id="targetInput"
            type="text"
            placeholder="enter youtube link..."
          ><br><br>
        <div class="validStatus" id="validStatus"></div><br>
        <button onclick="hideButton()"  id="playButton">play</button>
      </div>
    </div>
    <script>
      launchFeed=val=>{
        switch(val){
          case 0:
            ytid='KgIrF3gFt7I'
            sq = 384698
          break
          case 1:
            ytid='2ltUlpdAw-I'
            sq = 1687024
          break
          case 2:
            ytid='86YLFOog4GM'
            sq = 92510
          break
          case 3:
            ytid='QjVLMU9MdOY'
            sq = 636058
          break
        }
        targetInput.value='https://youtu.be/'+ytid
        targetInput.style.display='none'
        validStatus.style.display='none'
        validate()
        hideButton()
      }
      ytid=''
      if((s=window.location.href.split('ytid=')).length>1){
        ytid=s[1].split('&')[0]
      }
      function unicodeToChar(text) {
        return text.replace(/\\u[\dA-F]{4}/gi, 
          function (match) {
               return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
          });
      }
      validStatus = document.querySelector('#validStatus')
      targetInput = document.querySelector('#targetInput')
      playButton = document.querySelector('#playButton')
      linkInputContainer = document.querySelectorAll('.linkInputContainer')[0]
      streamID=()=>{
        if(validate()){
          let id
          let url = targetInput.value
          if(url.indexOf('youtu.be/')!==-1){
            id = url.split('youtu.be/')[1].split('&')[0]
          } else {
            id = url.split('?v=')[1].split('&')[0]
          }
          return id
        }
      }
      playMaybe=e=>{
        if(e.keyCode==13 && validate()){
          linkInputContainer.style.display='none'
          getStream(streamID())
        }
      }
      function validURL(str) {
        var regex = /(?:https?):\/\/(\w+:?\w*)?(\S+)(:\d+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if(!regex .test(str)) {
          return false;
        } else {
          return true;
        }
      }
      validate=()=>{
        targetInput.value=targetInput.value.trim()
        let val = targetInput.value
        let valid = false
        valid = validURL(val)
        if(val){
          validStatus.style.background = valid ? '#0f84' : '#f004'
          validStatus.style.color = valid ? '#8ff' : '#faa'
          validStatus.innerHTML = valid ? 'URL is valid!' : 'URL is <b>NOT</b> valid'
          playButton.style.color = valid ? '#4f8' : '#888'
          playButton.style.background = valid ? '#8f84' : '#333'
        } else {
          validStatus.innerHTML =''
          playButton.style.color = valid ? '#4f8' : '#888'
          playButton.style.background = valid ? '#8f84' : '#333'
        }
        return valid
      }
      hideButton=()=>{
        linkInputContainer.innerHTML = 'loading...'
				getStream(streamID())
      }
      main=document.querySelectorAll('.main')[0]
      c=document.querySelector('#c')
      x=c.getContext('2d')
      S=Math.sin
      C=Math.cos
      t=playing=0
      rsz=window.onresize=()=>{
        setTimeout(()=>{
          if(document.body.clientWidth > document.body.clientHeight*1.77777778){
            c.style.height = '100vh'
            setTimeout(()=>c.style.width = c.clientHeight*1.77777778+'px',0)
          }else{
            c.style.width = '100vw'
            setTimeout(()=>c.style.height = c.clientWidth/1.77777778 + 'px',0)
          }
          //c.width=1920x`
          //c.height=c.width/1.777777778
        },0)
      }
      rsz()

      factorSrc=()=>{
        let src = base
        src+='&cpn=zCGJpf2W6OYxfQya'
        src+='&sq='
        src+=sq
        src+='&rn='
        src+=rn
        src+='&rbuf='
        src+=rbuf
        return "/proxy.php/?url=" + src
      }
      recover=()=>{
        if(!vid.src && !buffer.src) return
        if(vid.src) vid.src=vid.src+'&1'
        if(buffer.src) buffer.src=buffer.src+'&1'
        //vid.muted=true
        //buffer.muted=true
        vid.oncanplay=()=>vid.play()
        buffer.oncanplay=()=>buffer.play()
        vid.play()
        buffer.play()
      }
      queueNext=()=>{
        vid = buffer
        src=[1, vid]
        //main.innerHTML=''
        //main.appendChild(vid)
        vid.play()
        sq++
        rn+=2
        rbuf=5000
        buffer=document.createElement('video')
        //buffer.muted = true
        buffer.onerror=recover
        buffer.onended=()=>{
          queueNext()
        }
        buffer.src=factorSrc()
      }
      getStream=(id)=>{
        let sendData={id}
        fetch('getLiveStreamPosition.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        }).then(res=>res.json()).then(data=>{
          if(data.length>1){
            rn = 1
            base = unicodeToChar(data[1])
            rbuf = 10000
            
            linkInputContainer.style.display='none'
            vid=document.createElement('video')
            buffer=document.createElement('video')
            vid.src=factorSrc()
            sq++
            buffer.src=factorSrc()
            //main.appendChild(vid)
            //vid.muted = true
            //buffer.muted = true
            vid.onerror=recover
            vid.onended=()=>{
              queueNext()
            }
            buffer.onerror=recover
            buffer.onended=()=>{
              queueNext()
            }
            vid.play()
            Draw()
          }
        })
      }

      Draw=()=>{
        if(!t){
          modes = []
          //modes.push(...(l=window.location.href.split('?'))[l.length-1].split('&'))
          window.location.href.split('?').map(v=>{
            v.split('&').map(q=>{
              modes.push(q)
            })
          })
          intensities={
            wavey: .75,
            twirl: .5,
            vignette: .5,
            scanlines: .9,
            matrix: .9
          }
          modes=modes.map(v=>{
            v=(l=v.split('='))[0]
            if(l.length>1 && typeof +l[1] == 'number') intensities[v] = +l[1]
            return v
          })
          modes = [...new Set(modes)]
          if(modes.indexOf('matrix') !== -1) modes = modes.filter(v=>v!='scanlines')
          if(modes.indexOf('twirl') !== -1) modes = modes.filter(v=>v!='wavey')
          tc = document.createElement('canvas')
          if(1||
            srcurl.toLowerCase().indexOf('.mp4') !== -1 ||
            srcurl.toLowerCase().indexOf('.webm') !== -1 ||
            srcurl.toLowerCase().indexOf('.mov') !== -1){
              type='vid'
              c.width = 1920/5|0
              c.height = 1080/5|0
              tc.width = c.width
              tc.height = c.height
              tcx = tc.getContext('2d')
              src=[1, vid]
              //src[1].oncanplay=()=>{
              //  src[0]=true
                //src[1].loop = true
                //src[1].muted = true
                //src[1].play()
              //}
          } else {
            if(modes.indexOf('wavey')!==-1 || modes.indexOf('twirl')!==-1){
              if(modes.indexOf('wavey')!==-1){
                modes = modes.filter(v=>v!=='wavey')
                modes = [...modes, 'wavey']
              }
              c.width = 1920/5|0
              c.height = 1080/5|0
            } else {
              c.width = 1920|0
              c.height = 1080|0
            }
            tc.width = c.width
            tc.height = c.height
            tcx = tc.getContext('2d')
          }
          //src[1].src="/proxy.php/?url=<?=$url?>"
        }
      
        if(modes.indexOf('vignette')!==-1){
          modes = modes.filter(v=>v!=='vignette')
          modes = [...modes, 'vignette']
        }
        if(src[0]){
          tcx.drawImage(src[1],0,0,c.width,c.height)
          d2 = tcx.getImageData(0,0,tc.width,tc.height)
          for(ct=i=0;i<d2.data.length;i+=4){
            a = d2.data
            red   = a[i+0]
            green = a[i+1]
            blue  = a[i+2]
            alpha = a[i+3]
            X=(i/4|0)%c.width
            Y=(i/4/c.width|0)
            ref=d2
            modes.map(v=>{
              switch(v){
                case 'matrix':
                  red   = a[i+0]
                  green = a[i+1]
                  blue  = a[i+2]
                  alpha = a[i+3]
                  luminosity = (red+green+blue)/3 |0
                  red   = 0
                  green = luminosity * (f=((1-intensities['matrix']/2)-S(p=Math.PI*2/c.height*(c.height/2-Y)*(35-S(t*4)*20)+Math.PI/2)*(1-(1-intensities['matrix']/2))))
                  blue  = green / 3 |0
                  a[i+0] = red
                  a[i+1] = green
                  a[i+2] = blue
                  a[i+3] = 255
                break
                case 'vignette':
                  red   = a[i+0]
                  green = a[i+1]
                  blue  = a[i+2]
                  alpha = a[i+3]
                  cols=[red, green, blue]
                  red   = red   * (f=((.5-S(p=Math.PI*2/c.width*X+Math.PI/2)/2)*(.5-S(p=Math.PI*2/c.height*Y+Math.PI/2)/2))**intensities['vignette'])
                  green = green * f
                  blue  = blue  * f
                  a[i+0] = red
                  a[i+1] = green
                  a[i+2] = blue
                  a[i+3] = 255
                break
                case 'pink':
                  red   = a[i+0]
                  green = a[i+1]
                  blue  = a[i+2]
                  alpha = a[i+3]
                  luminosity = (red+green+blue)/3 |0
                  red   = luminosity
                  green = luminosity/2|0
                  blue  = luminosity/2|0
                  a[i+0] = red
                  a[i+1] = green
                  a[i+2] = blue
                  a[i+3] = 255
                break
                case 'scanlines':
                  red   = red   * (f=((1-intensities['scanlines']/2)-S(p=Math.PI*2/c.height*(c.height/2-Y)*(35-S(t*4)*20)+Math.PI/2)*(1-(1-intensities['scanlines']/2))))
                  green = green * f
                  blue  = blue  * f
                  a[i+0] = red
                  a[i+1] = green
                  a[i+2] = blue
                  //a[i+3] = 255
                break
              }
            })
            ct++
          }
          if(modes.indexOf('wavey') !== -1) {
            d1 = tcx.getImageData(0,0,tc.width,tc.height)
            for(ct=i=0;i<d2.data.length;i+=4){
              X=(i/4|0)%c.width
              Y=(i/4/c.width|0)
              ref=d2
              b=d1.data
              d=Math.hypot(c.width/2-X,c.height/2-Y)
              p=Math.atan2(c.width/2-X,c.height/2-Y)
              s=20*intensities['wavey']//(1+d/2000)
              tx=X+S(q=p)*(m=(1+S(d/(30+S(t)*25)-t*20)/2)*s*Math.min(1,d/100))
              ty=Y+C(q)*m
              n=((ty|0)*c.width+(tx|0))*4
              b[i+0] = a[n+0]
              b[i+1] = a[n+1]
              b[i+2] = a[n+2]
              //b[i+3] = 255
              ref=d1
              ct++
            }
          }
          if(modes.indexOf('twirl') !== -1) {
            d1 = tcx.getImageData(0,0,tc.width,tc.height)
            for(ct=i=0;i<d2.data.length;i+=4){
              X=(i/4|0)%c.width
              Y=(i/4/c.width|0)
              ref=d2
              b=d1.data
              d=Math.hypot(e=c.width/2-X,f=c.height/2-Y)
              p=Math.atan2(e,f)+Math.PI+2.5/(1+d**3.9/4e6)*S(t*4)*intensities['twirl']*2
              tx=c.width/2+S(p)*d
              ty=c.height/2+C(p)*d
              n=((ty|0)*c.width+(tx|0))*4
              b[i+0] = a[n+0]
              b[i+1] = a[n+1]
              b[i+2] = a[n+2]
              //b[i+3] = 255
              ref=d1
              ct++
            }
          }
          x.putImageData(ref,0,0)
        }
        t+=1/60
        requestAnimationFrame(Draw)
      }
      if(ytid){
        targetInput.value='https://youtu.be/'+ytid
        targetInput.style.display='none'
        validStatus.style.display='none'
        //hideButton()
        validate()
      }
    </script>
  </body>
</html>