<!doctype html>

<html>
   <head>
      <meta charset="utf-8">
      <title>Legu Hubu</title>
   </head>
   <body>
      <!-- Översta delen av sidan. Innehåller Logo, navigation och användarbild -->
      <div id="header">
         <div id="logo">
            Legu
         </div>
         <div id="nav">
            <div class="navOption">
               <p>Home</p>
            </div>
            <div class="navOption">
               <p>Sets</p>
            </div>
            <div class="navOption">
               <p>Bricks</p>
            </div>
         </div>
         <div id="profile">
            Profile
         </div>
      </div>
      <!-- Sidinnehållet -->
      <div id="page">
         <!-- Knapparna med statistik om satser och bitar -->
         <div class="wrapper" id="amountsWrapper">
            <div class="amountBox">
               <p>Sets</p>
               <p>2330</p>
            </div>
            <div class="amountBox">
               <p>Parts</p>
               <p>233034</p>
            </div>
         </div>
         <!-- Infotext -->
         <div class="wrapper" id="textBox">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.
               Vestibulum porttitor risus nec convallis faucibus.
               Nunc suscipit interdum mi non auctor. Sed blandit libero justo, id mollis justo eleifend eget.
               Duis imperdiet, nulla ut gravida dictum, tortor ex pretium nisi, non laoreet dui ligula volutpat mauris.</p>
         </div>
         <!--Diagram-->
         <div id="diagrams">
            <p>Some statistics</p>

            <canvas class="diagram" height="200px" width="400px"></canvas>

            <p>Lite mer text</p>

            <canvas class="diagram" height="200px" width="400px"></canvas>

            <p>Lite ännu mera text :D</p>
         </div>
      </div>
   </body>
</html>
