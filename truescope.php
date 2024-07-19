<?php 
   session_start();
   include("php/config.php");

   if (!isset($_SESSION['valid']) ) {
       header("Location: truescope.php");
       exit;
   }

   $user_id = $_SESSION['id'];

   
   $profilePicPath = 'assets/icon3.png'; 
   $stmt = $con->prepare("SELECT file_path FROM profile_pic WHERE user_id = ? AND status = 'active' ORDER BY upload_date DESC LIMIT 1");
   $stmt->bind_param('i', $user_id);
   $stmt->execute();
   $stmt->bind_result($dbProfilePicPath);
   if ($stmt->fetch() && !empty($dbProfilePicPath)) {
       $profilePicPath = htmlspecialchars($dbProfilePicPath);
   }
   $stmt->close();

   
   $query = $con->prepare("SELECT Username, Email, Age FROM users WHERE Id = ?");
   $query->bind_param('i', $user_id);
   $query->execute();
   $query->bind_result($res_Uname, $res_Email, $res_Age);
   $query->fetch();
   $query->close();

            
            $id = $_SESSION['id'];
            $query = mysqli_query($con,"SELECT*FROM users WHERE Id=$id");

            while($result = mysqli_fetch_assoc($query)){
                $res_Uname = $result['Username'];
                $res_Email = $result['Email'];
                $res_Age = $result['Age'];
                $res_id = $result['Id'];
            }
   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>true scope</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
    <nav>
        <div class="main-nav container">
            <button id="toggle-footer" class="toggle-footer-button"></button>
            <a href="#" onclick="reload()" class="company-logo">
                <img src="./assets/logo.png" alt="company logo">
            </a>
            <div class="nav-links">
                <ul class="flex">
                    <li class="hover-link nav-item" id="home" onclick="showHome()">Home</li>
                    <li class="hover-link nav-item" id="ipl" onclick="onNavItemClick('ipl')">IPL</li>
                    <li class="hover-link nav-item" id="finance" onclick="onNavItemClick('finance')">Finance</li>
                    <li class="hover-link nav-item" id="politics" onclick="onNavItemClick('politics')">Politics</li>
                    <li class="hover-link nav-item" id="health" onclick="onNavItemClick('health')">health</li>
                </ul>
            </div>

            <div class="search-bar flex">
                <input id="search-text" type="text" class="news-input" placeholder="Search">
                <button id="search-button" class="search-button">
                <i class="fas fa-search"></i>
                </button>
            </div>
            <a href="index.php"> <button class="icon1"></button> </a>
            <a href="php/logout.php"> <button class="icon2"></button> </a>
        </div>
        <div class="profile-dropdown" id="profile">
        <img src="<?php echo htmlspecialchars($profilePicPath); ?>" alt="Profile Picture" class="profile-pic" id="profile-pic">
        <div class="dropdown-content" id="dropdown-content">
        <h3>Change Profile Picture:</h3><br>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <input type="file" name="profile_pic" id="profile_pic" class="search-button" accept="image/*" required><br>
                <button type="submit" class="search-button">Upload</button>
                <h3>__________________________</h3>
            </form><br>
                <h3>User Details</h3><br>
                <?php 
                if ($res_Uname) {
                    echo "<p>Welcome back, " . htmlspecialchars($res_Uname) . "!</p>";
                } else {
                    echo "<p>Welcome back, guest!</p>";
                }
                ?>
                <p>Your logged in email <b><?php echo $res_Email ?></b>.</p>
                <p>Want to update your profile <?php echo "<a href='edit.php?Id=$res_id'>click here!!!</a>";  ?>
                <h3>__________________________</h3>
                <br>
                <h3>Check some of your saved articles</h3><br>
                <button onclick="saved_news()" class="search-button">Saved news</button>
                <!-- Add more details as needed -->
            </div>
        </div>
    </nav><br><br>

    <div class="body-content">
    <div id="slideshow-div">

        <div class="slideshow-container">

            <div class="mySlides fade">
            <img src="" id="slide-img1">
            </div>
            
            <div class="mySlides fade">
            <img src="" id="slide-img2">
            </div>
            
            <div class="mySlides fade">
            <img src="" id="slide-img3">
            </div>
            
        </div>
            <br>
            
        <div style="text-align:center" class="caption">
            <div class="dot"  id="caption1"></div> 
            <div class="dot"  id="caption2"></div> 
            <div class="dot"  id="caption3"></div> 
        </div>
    </div>
    

    <div class="cards-container container flex" id="cards-container-saved">
        <template id="template-news-card-saved">
            <div class="card">
                <div class="card-header">
                    <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-saved">
                </div>
                <div class="card-content">
                    <h3 id="news-title-saved">This is the Title</h3>
                    <h6 class="news-source-saved" id="news-source-saved">End Gadget 26/08/2023</h6>
                    <p class="news-desc-saved" id="news-desc-saved">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>
                    <div class="card-actions">
                        <div class="dots" onclick="toggleDots(this)">•••</div>
                        <div class="actions">
                            <button id="remove-saved" onclick="actionRemoveSave()">Remove from Saved</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>         
    </div>


    <div class="cards-container container flex" id="cards-container-main">
        <template id="template-news-card-main">
            <div class="card">
                <div class="card-header">
                    <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-main">
                </div>
                <div class="card-content">
                    <h3 id="news-title-main">This is the Title</h3>
                    <h6 class="news-source-main" id="news-source-main">End Gadget 26/08/2023</h6>
                    <p class="news-desc-main" id="news-desc-main">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>
                    <div class="card-actions">
                        <div class="dots" onclick="toggleDots(this)">•••</div>
                        <div class="actions">
                            <button id="save-main">Save for later</button>
                            <button onclick="actionShare()">Share</button>
                            <button onclick="actionGoToLink()">Go to Link</button>
                        </div>
                    </div>
                </div>
            </div>
        </template>         
    </div>


    <main>
        <div id="trending-div">
            <div class="trending" id="trending">
                <button class="trending-button" id="trending-button" style="vertical-align:middle" onclick="toggleTrendingButton()">
                    <span id="t-span">Trending India</span>
                </button>
            </div>
            
            <div class="cards-container container flex" id="cards-container">
                <template id="template-news-card">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img">
                        </div>
                        <div class="card-content">
                            <h3 id="news-title">This is the Title</h3>
                            <h6 class="news-source" id="news-source">End Gadget 26/08/2023</h6>
                            <p class="news-desc" id="news-desc">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p><br><br>


                            
                            <h3>Comments</h3>
                            <div class="comments-section">
                                <div class="comment-form">
                                        <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
                                        <input type="hidden" name="news_id" value="1"> <!-- Replace with dynamic news_id -->
                                        <input type="text" name="comment" id="comment-trend" placeholder="Add your comment">
                                        <button id="submit-comment">Submit</button>

                                        <br><br><p id="comment-sec" class="comment"></p>

                                </div>                 
                            </div>
                             
                            <div class="card-actions">
                                <div class="dots" onclick="toggleDots(this)">•••</div>
                                <div class="actions">
                                    <button id="save-trend">Save for later</button>
                                    <button id="share-trend">Share</button>
                                    <button onclick="actionGoToLink()">Go to Link</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </template>         
            </div>
        </div>

        <div id="tech-div">
            <div class="tech" id="tech">
                <button class="tech-button" id="tech-button" style="vertical-align:middle" onclick="toggleTechButton()">
                    <span id="tech-span">Technology</span>
                </button>
            </div>
           
            <div class="cards-container container flex" id="cards-container-2">
                <template id="template-news-card-2">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-2">
                        </div>
                        <div class="card-content">
                            <h3 id="news-title-2">This is the Title</h3>
                            <h6 class="news-source-2" id="news-source-2">End Gadget 26/08/2023</h6>
                            <p class="news-desc-2" id="news-desc-2">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>

                            <h3>Comments</h3>
                            <div class="comments-section">
                                <div class="comment-form">
                                        <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
                                        <input type="hidden" name="news_id" value="1"> <!-- Replace with dynamic news_id -->
                                        <input type="text" name="comment" id="comment-trend" placeholder="Add your comment">
                                        <button id="submit-comment">Submit</button>

                                        <p id="comment-sec"></p>

                                </div>                 
                            </div>

                            <div class="card-actions">
                                <div class="dots" onclick="toggleDots(this)">•••</div>
                                <div class="actions">
                                    <button id="save-tech">Save for later</button>
                                    <button onclick="actionShare()">Share</button>
                                    <button onclick="actionGoToLink()">Go to Link</button>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </template>         
            </div>
        </div>

        <div id="health-div">
            <div class="health" id="health">
                <button class="health-button" id="health-button" style="vertical-align:middle" onclick="toggleHealthButton()">
                    <span id="health-span">Health</span>
                </button>
            </div>
            
            <div class="cards-container container flex" id="cards-container-health">
                <template id="template-news-card-health">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-health">
                        </div>
                        <div class="card-content">
                            <h3 id="news-title-health">This is the Title</h3>
                            <h6 class="news-source-health" id="news-source-health">End Gadget 26/08/2023</h6>
                            <p class="news-desc-health" id="news-desc-health">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>

                            <h3>Comments</h3>
                            <div class="comments-section">
                                <div class="comment-form">
                                        <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
                                        <input type="hidden" name="news_id" value="1"> <!-- Replace with dynamic news_id -->
                                        <input type="text" name="comment" id="comment-trend" placeholder="Add your comment">
                                        <button id="submit-comment">Submit</button>

                                        <p id="comment-sec"></p>

                                </div>                 
                            </div>

                            <div class="card-actions">
                                <div class="dots" onclick="toggleDots(this)">•••</div>
                                <div class="actions">
                                    <button id="save-health">Save for later</button>
                                    <button onclick="actionShare()">Share</button>
                                    <button onclick="actionGoToLink()">Go to Link</button>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </template>         
            </div>
        </div>

        <div id="edu-div">
            <div class="edu" id="edu">
                <button class="edu-button" id="edu-button" style="vertical-align:middle" onclick="toggleEduButton()">
                    <span id="edu-span">Education</span>
                </button>
            </div>
            
            <div class="cards-container container flex" id="cards-container-edu">
                <template id="template-news-card-edu">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-edu">
                        </div>
                        <div class="card-content">
                            <h3 id="news-title-edu">This is the Title</h3>
                            <h6 class="news-source-edu" id="news-source-edu">End Gadget 26/08/2023</h6>
                            <p class="news-desc-edu" id="news-desc-edu">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>

                            <h3>Comments</h3>
                            <div class="comments-section">
                                <div class="comment-form">
                                        <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
                                        <input type="hidden" name="news_id" value="1"> <!-- Replace with dynamic news_id -->
                                        <input type="text" name="comment" id="comment-trend" placeholder="Add your comment">
                                        <button id="submit-comment">Submit</button>

                                        <p id="comment-sec"></p>

                                </div>                 
                            </div>

                            <div class="card-actions">
                                <div class="dots" onclick="toggleDots(this)">•••</div>
                                <div class="actions">
                                    <button id="save-edu">Save for later</button>
                                    <button onclick="actionShare()">Share</button>
                                    <button onclick="actionGoToLink()">Go to Link</button>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </template>         
            </div>
        </div>

        <div id="sports-div">
            <div class="sports" id="sports">
                <button class="sports-button" id="sports-button" style="vertical-align:middle" onclick="toggleSportsButton()">
                    <span id="sports-span">Sports</span>
                </button>
            </div>
            
            <div class="cards-container container flex" id="cards-container-sports">
                <template id="template-news-card-sports">
                    <div class="card">
                        <div class="card-header">
                            <img src="https://via.placeholder.com/400x200" alt="news-image" id="news-img-sports">
                        </div>
                        <div class="card-content">
                            <h3 id="news-title-sports">This is the Title</h3>
                            <h6 class="news-source-sports" id="news-source-sports">End Gadget 26/08/2023</h6>
                            <p class="news-desc-sports" id="news-desc-sports">Lorem, ipsum dolor sit amet consectetur adipisicing elit. Recusandae saepe quis voluptatum quisquam vitae doloremque facilis molestias quae ratione cumque!</p>

                            <h3>Comments</h3>
                            <div class="comments-section">
                                <div class="comment-form">
                                        <input type="hidden" name="user_id" value="<?php echo $res_id; ?>">
                                        <input type="hidden" name="news_id" value="1"> <!-- Replace with dynamic news_id -->
                                        <input type="text" name="comment" id="comment-trend" placeholder="Add your comment">
                                        <button id="submit-comment">Submit</button>

                                        <p id="comment-sec"></p>

                                </div>                 
                            </div>
                            
                            <div class="card-actions">
                                <div class="dots" onclick="toggleDots(this)">•••</div>
                                <div class="actions">
                                    <button id="save-sports">Save for later</button>
                                    <button onclick="actionShare()">Share</button>
                                    <button onclick="actionGoToLink()">Go to Link</button>
                                </div>
                            </div>                        
                        </div>
                    </div>
                </template>         
            </div>
        </div>


    </main>
        


       <!--Blog cards-->

    <div class="main-2">

        <div class="col-1">
            <div class="top-news">
                <p>TOP NEWS</p>
            </div>

            <div class="blog-card">
                <div class="meta">
                <div class="photo" id="blog-img"></div>
                <ul class="details">
                    <li class="author" id="blog-source">News source</li>
                    <li class="date" id="blog-date">date here</li>                
                </ul>
                </div>
                <div class="description">
                <h1 id="blog-title">This is the Title</h1>
                <p id="blog-desc"> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ad eum dolorum architecto obcaecati enim dicta praesentium, quam nobis! Neque ad aliquam facilis numquam. Veritatis, sit.</p>
                <p class="read-more" id="read-more">
                    <a href="#">Read More</a>
                </p>
                </div>
            </div>
        </div>

        <div class="col-2">
            <div class="latest-news">
                <p>INTERNATIONAL</p>
            </div>

            <div class="blog-card">
                <div class="meta">
                <div class="photo" id="blog-img-2"></div>
                <ul class="details">
                    <li class="author" id="blog-source-2">News source</li>
                    <li class="date" id="blog-date-2">date here</li>                
                </ul>
                </div>
                <div class="description">
                <h1 id="blog-title-2">This is the Title</h1>
                <p id="blog-desc-2">....... </p>
                <p class="read-more" id="read-more-2">
                    <a href="#">Read More</a>
                </p>
                </div>
            </div>
        </div>  

    </div>
    
    <script src="script.js"></script>
    </div>

    <!--footer-->
    <br><br><br><br><footer class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">world</h4>
                    <ul>
                        <li class="hover-link nav-item" id="america" onclick="handleNavItemClick('america');" >AMERICA</li>
                        <li class="hover-link nav-item" id="india" onclick="onNavItemClick('india')">INDIA</li>
                        <li class="hover-link nav-item" id="russia" onclick="onNavItemClick('russia')">RUSSIA</li>
                        <li class="hover-link nav-item" id="germany" onclick="onNavItemClick('germany')">GERMANY</li>
                        <li class="hover-link nav-item" id="australia" onclick="onNavItemClick('australia')">AUSTRALIA</li>
                        <li class="hover-link nav-item" id="africa" onclick="onNavItemClick('africa')">AFRICA</li>
                        <li class="hover-link nav-item" id="china" onclick="onNavItemClick('china')">CHINA</li>
                        <li class="hover-link nav-item" id="united kingdom" onclick="onNavItemClick('united kingdom')">UNITED KINGDOM</li>
                        
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">sports</h4>
                    <ul>
                        <li class="hover-link nav-item" id="cricket" onclick="onNavItemClick('cricket')">CRICKET</li>
                        <li class="hover-link nav-item" id="football" onclick="onNavItemClick('football')">FOOTBALL</li>
                        <li class="hover-link nav-item" id="tennis" onclick="onNavItemClick('tennis')">TENNIS</li>
                        <li class="hover-link nav-item" id="motorsport" onclick="onNavItemClick('motorsport')">MOTORSPORT</li>
                        <li class="hover-link nav-item" id="olympics" onclick="onNavItemClick('olympics')">OLYMPICS</li>
                        <li class="hover-link nav-item" id="esports" onclick="onNavItemClick('esports')">ESPORTS</li>
                        <li class="hover-link nav-item" id="hockey" onclick="onNavItemClick('hockey')">HOCKEY</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">health</h4>
                    <ul>
                        <li class="hover-link nav-item" id="life" onclick="onNavItemClick('life')">LIFE</li>
                        <li class="hover-link nav-item" id="fitness" onclick="onNavItemClick('fitness')">FITNESS</li>
                        <li class="hover-link nav-item" id="food" onclick="onNavItemClick('food')">FOOD</li>
                        <li class="hover-link nav-item" id="sleep" onclick="onNavItemClick('sleep')">SLEEP</li>
                        <li class="hover-link nav-item" id="mind" onclick="onNavItemClick('mind')">MINDFULLNESS</li>
                        <li class="hover-link nav-item" id="relaxation" onclick="onNavItemClick('relaxation')">RELAXATION</li>
                        <li class="hover-link nav-item" id="relationships" onclick="onNavItemClick('relationships')">RELATIONSHIPS</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">style</h4>
                    <ul>
                        <li class="hover-link nav-item" id="arts" onclick="onNavItemClick('arts')">ARTS</li>
                        <li class="hover-link nav-item" id="design" onclick="onNavItemClick('design')">DESIGN</li>
                        <li class="hover-link nav-item" id="architecture" onclick="onNavItemClick('architecture')">ARCHITECTURE</li>
                        <li class="hover-link nav-item" id="fashion" onclick="onNavItemClick('fashion')">FASHION</li>
                        <li class="hover-link nav-item" id="luxury" onclick="onNavItemClick('luxury')">LUXURY</li>
                        <li class="hover-link nav-item" id="beauty" onclick="onNavItemClick('beauty')">BEAUTY</li>
                    </ul><br><br><br><br><br><br><br><br>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">entertainment</h4>
                    <ul>
                        <li class="hover-link nav-item" id="celebrity" onclick="onNavItemClick('celebrity')">CELEBRITY</li>
                        <li class="hover-link nav-item" id="bollywood" onclick="onNavItemClick('bollywood')">BOLLYWOOD</li>
                        <li class="hover-link nav-item" id="hollywood" onclick="onNavItemClick('hollywood')">HOLLYWOOD</li>
                        <li class="hover-link nav-item" id="television" onclick="onNavItemClick('television')">TELEVISION</li>
                        <li class="hover-link nav-item" id="movies" onclick="onNavItemClick('movies')">MOVIES</li>        
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">science</h4>
                    <ul>
                        <li class="hover-link nav-item" id="technology" onclick="onNavItemClick('technology')">CELEBRITY</li>
                        <li class="hover-link nav-item" id="space" onclick="onNavItemClick('space')">SPACE</li>
                        <li class="hover-link nav-item" id="aeronautics" onclick="onNavItemClick('aeronautics')">AERONAUTICS</li>
                        <li class="hover-link nav-item" id="biological" onclick="onNavItemClick('biological')">BIOLOGICAL</li>
                        <li class="hover-link nav-item" id="chemistry" onclick="onNavItemClick('chemistry')">CHEMISTRY</li>
                        <li class="hover-link nav-item" id="automobiles" onclick="onNavItemClick('automobiles')">AUTOMOBILES</li>
                        <li class="hover-link nav-item" id="development" onclick="onNavItemClick('development')">DEVELOPMENT</li> 
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 style="color: rgb(5, 5, 254)">education</h4>
                    <ul>
                        <li class="hover-link nav-item" id="schools" onclick="onNavItemClick('schools')">SCHOOLS</li>
                        <li class="hover-link nav-item" id="college" onclick="onNavItemClick('college')">COLLEGE</li>
                        <li class="hover-link nav-item" id="jobs" onclick="onNavItemClick('jobs')">JOBS</li>
                        <li class="hover-link nav-item" id="graduation" onclick="onNavItemClick('graduation')">GRADUATES</li>
                    </ul>
                </div>
                
            </div>
        </div>
   </footer>
 
</body>
</html> 