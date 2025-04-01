<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--==title========================-->
    <title>Lifeline Care Center</title>
    <!--==Fav-icon=====================-->
    <link rel="shortcut icon" href="images/titleIcon.png">
    <!--==CSS==========================-->
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!--==Font-Awesome-for-icons=====-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <!-- Link Swiper's CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css"
    />
    
    <style>
        /* email subscribtion form css */
    .subscription-message {
    margin-top: 15px;
    padding: 10px 20px;
    border-radius: 5px;
    text-align: center;
    animation: fadeIn 0.3s ease-in;
    }

    .subscription-message.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    }

    .subscription-message.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    }
    .nav-login-btn{
    height: 45px;
    padding: 0px 20px;
    border-radius:4px;
    background-color: #72E8D2;
    color: #000000;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: 500;
    letter-spacing: 1px;
    font-size: 0.9rem;
    margin-left: 20px;
}
.nav-login-btn:hover{
    background-color: #5AC0B2;
    transition: 0.5s;
}
.chatbot-container {
  width: 550px;
  height: 570px;
  position: fixed;
  bottom: 80px;
  right: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  background-color: white;
  display: none;
  flex-direction: column;
  z-index: 1000;
}

.chat-header {
  background-color: #D54A6A;
  color: white;
  padding: 12px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.chat-body {
  flex: 1;
  padding: 17px;
  overflow-y: auto;
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
}

.chat-footer {
  display: flex;
  padding: 15px;
  gap: 10px;
}

.chat-footer input {
  flex: 1;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.chat-footer button {
  padding: 10px 14px;
  background-color: #D54A6A;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.chat-footer button:hover {
  background-color: #B03C57;
}

.chatbot-toggle {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #D54A6A;
  color: white;
  padding: 16px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
}

.chatbot-toggle:hover {
  background-color: #B03C57;
}

.message {
  margin: 8px 0;
  padding: 10px;
  border-radius: 5px;
}

.message.user {
  background-color: #D54A6A;
  color: white;
  align-self: flex-end;
}

.message.bot {
  background-color: #f1f1f1;
  align-self: flex-start;
}
.chat-suggestions {
  display: flex;
  flex-wrap: wrap;
  gap: 5px;
  padding: 10px;
  border-top: 1px solid #ddd;
  background-color: #f9f9f9;
}

.chat-suggestions button {
  padding: 5px 10px;
  background-color: #D54A6A;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 12px;
}
.btnClose
{
    background-color: white;
    color: black;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    padding: 5px 7px;
    font-size: 20px;
    line-height: 20px;
    margin: 0;
    display: inline-block;
    position: absolute;
    top: 5px;
    right: 5px;
}
.chat-suggestions button:hover {
  background-color: #B03C57;
}
.caregiver-search-container{
    position: relative; /* Ensure search bar doesn't interfere */
  z-index: 1; /* Lower than chatbot */
  margin-bottom: 20px;
}


    @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
    }
    </style>

</head>
<body>
    <div class="chatbot-container">
        <div class="chat-header">
          <h3>Chatbot</h3>
          <button id="close-chat" class="btnClose">×</button>
        </div>
        <div class="chat-body" id="chat-body">
          <!-- Messages will appear here -->
        </div>
        <div class="chat-suggestions" id="chat-suggestions">
            <!-- Suggestion buttons will appear here -->
          </div>
        <div class="chat-footer">
          <input type="text" id="chat-input" placeholder="Type a message...">
          <button id="send-btn">Send</button>
        </div>
      </div>
    
      <button class="chatbot-toggle" id="chatbot-toggle">Chat</button>
    
    <!--==Hero-Section========================-->
    <section id="hero">

        <!--==navigation====================-->
        <nav class="navigation">
            <!--**menu-btn*****-->
            <input type="checkbox" class="menu-btn" id="menu-btn">
            <label for="menu-btn" class="menu-icon">
                <span class="nav-icon"></span>
            </label>
            <!--**logo*********-->
            <a href="index.html" class="logo"><img src="images/logo.png" alt="Lifeline Care Logo"></a>
            <!--**menu*********-->
            <ul class="menu">
                <li><a href="#hero">Home</a></li>
                <li><a href="#our-services">Our Services</a></li>
                <li><a href="#testimonials">FeedBack</a></li>
                <li><a href="#our-faq">FAQ</a></li>
                <li><a href="#about-us">About Us</a></li>
            </ul>
            <!--**contact*******-->
            <a href="userProfile.php" class=""><i class="fa-solid fa-circle-user" style="font-size: 34px; color: #D54A6A;"></i></a>
            <a href="sign-in.html" class="nav-login-btn">Logout Now</a>
        </nav><!--nav-end-->


        <!--==Content============================-->
        <div class="hero-content">
            <!--**text****************-->
            <div class="hero-text">
                <h1>Personalized Care for Your Loved Ones</h1>
                <p>We provide dedicated nurses and caregivers to support the elderly and sick at home. Compassion, trust, and care—delivered where it matters most.</p>
                <!--btns-->
                <div class="hero-text-btns">
                    <a href="booking.php"><i class="fa-solid fa-stethoscope"></i> Hire a Caregiver</a>
                    
                </div>
            </div>
            <!--**img*****************-->
            <div class="hero-img">
                <img src="images/pic1.png" alt="A nurse giving care">
            </div>
        </div>

        
    </section><!--hero-end-->


    <!--==caregiver-search===============================-->
    <div class="caregiver-search-container">
        <h3>Find Trusted Caregivers Near You</h3>
        <form class="caregiver-search">
        <!--**cargiver-search-box*******-->
        <div class="caregiver-search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input id="caregiver-search" type="text" placeholder=" Search for Caregivers or Personal Nurses">
        </div>
        <!--**set-your-location*******-->
        <div class="caregiver-search-box">
            <i class="fa-solid fa-location-dot"></i>
            <input id="location-search" type="text" placeholder=" Enter Your Location to Find Home Care Services">
        </div>
        <!--**btn*********************-->
        <button>
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        </form>
    </div>


    <!--==service-info===========================-->
    <section class="what-we-provide">

        <!--**box1***********-->
        <div class="w-info-box w-i-box1">
            <!--icon-->
            <div class="w-info-icon">
                <i class="fa-solid fa-user-nurse"></i>
            </div>
            <!--text-->
            <div class="w-info-text">
                <strong>Qualified Professionals</strong>
                <p>Experienced and certified caregivers. </p>
            </div>
        </div>
        <!--**box2***********-->
        <div class="w-info-box w-i-box2">
            <!--icon-->
            <div class="w-info-icon">
                <i class="fa-solid fa-house-medical"></i>
            </div>
            <!--text-->
            <div class="w-info-text">
                <strong>24/7 Availablity</strong>
                <p>Access care services round-the-clock. </p>
            </div>
        </div>
        <!--**box3***********-->
        <div class="w-info-box w-i-box3">
            <!--icon-->
            <div class="w-info-icon">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
            <!--text-->
            <div class="w-info-text">
                <strong>Personalized Care Plans</strong>
                <p>Tailored to your individual requirements. </p>
            </div>
        </div>
    
    </section><!--end-->



    <!--==about us=============================-->
    <section id="about-us">

        <!--**img**-->
        <div class="about-us-img">
            <img src="images/aboutPic1.webp" />
            
        </div>
        <!--**text**-->
        <div class="about-us-text">
            <h2>Expert Care, Trusted Hands</h2>
            <p>With years of experience in the healthcare industry, Lifeline Care Center has become a trusted name in providing high-quality care. Our team of highly skilled professionals is equipped with the latest knowledge and techniques to ensure optimal health and well-being. </p>
            <p>From managing chronic conditions to promoting independence, we offer a comprehensive range of services designed to meet your specific needs. Experience the difference that expert care can make. <a href="AboutUsPage.html">Read More</a></p>
            
            <div class="about-us-container">
                <!--box-->
                <div class="story-numbers-box s-n-box1">
                    <strong>100+</strong>
                    <span>Certified Caregivers</span>
                </div>
                <!--box-->
                <div class="story-numbers-box s-n-box2">
                    <strong>10+</strong>
                    <span>Years of Experience</span>
                </div>
                <!--box-->
                <div class="story-numbers-box s-n-box3">
                    <strong>15+</strong>
                    <span>Districts Covered</span>
                </div>
                <!--box-->
                <div class="story-numbers-box s-n-box4">
                    <strong>500+</strong>
                    <span>Satisfied Clients</span>
                </div>
            </div>
        </div>
        
    </section><!--about-us end----->

    

    <!--==Our-Services======================-->
    <section id="our-services">

        <!--**heading********************-->
        <div class="services-heading">
            <!--text-->
            <div class="services-heading-text">
                <strong>Our Services</strong>
                <h2>High Quality Services For You</h2>
            </div>
            <!--btns-->
            <div class="service-slide-btns">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <!--**container*******************-->
        <div class="services-box-container">

             <!-- Swiper -->
            <div class="swiper mySwiperservices">
                <div class="swiper-wrapper">
                    <!--**1*******-->
                    <div class="swiper-slide">
                    <!--**box1**-->
                    <div class="service-box s-box1">
                        <!--icon-->
                        <i class="fa-solid fa-heart-pulse"></i>
                        <!--title-->
                        <strong> 24/7 Nursing Care</strong>
                        <!--details-->
                        <p>Our experienced nurses provide round-the-clock care for patients of all ages. 
                            We offer a range of services, including medication administration, wound care, and vital sign monitoring.</p>
                        <!--learn more-->
                        <a href="servicesPage.html#nursing-care">Learn More</a>
                    </div>
                    </div><!--slide-end-->
                    <!--**1*******-->
                    <div class="swiper-slide">
                    <!--**box1**-->
                    <div class="service-box s-box2">
                        <!--icon-->
                        <i class="fa-solid fa-house-medical"></i>
                        <!--title-->
                        <strong> In-Home Care</strong>
                        <!--details-->
                        <p>Our compassionate caregivers provide personalized assistance with daily living activities, 
                            such as bathing, dressing, and meal preparation. We also offer companionship and respite care.</p>
                        <!--learn more-->
                        <a href="servicesPage.html#in-home-care">Learn More</a>
                    </div>
                    </div><!--slide-end-->
                    <!--**1*******-->
                    <div class="swiper-slide">
                    <!--**box1**-->
                    <div class="service-box s-box3">
                        <!--icon-->
                        <i class="fa-solid fa-briefcase-medical"></i>
                        <!--title-->
                        <strong>Home Health Aide Services</strong>
                        <!--details-->
                        <p>Our dedicated home health aides provide essential assistance with daily living activities, 
                            light housekeeping, and meal preparation. We can help maintain a clean and safe home environment.</p>
                        <!--learn more-->
                        <a href="servicesPage.html#home-health-aide">Learn More</a>
                    </div>
                    </div><!--slide-end-->
                    <!--**1*******-->
                    <div class="swiper-slide">
                    <!--**box1**-->
                    <div class="service-box s-box4">
                        <!--icon-->
                        <i class="fa-regular fa-calendar-check"></i>
                        <!--title-->
                        <strong>Flexible Care Plans</strong>
                        <!--details-->
                        <p>We offer a variety of flexible care plans to meet your unique needs. Whether you need short-term respite 
                            care or long-term support, we can create a customized plan that works for you.</p>
                        <!--learn more-->
                        <a href="servicesPage.html#flexible-care">Learn More</a>
                    </div>
                    </div><!--slide-end-->
                    <!--**1*******-->
                    <div class="swiper-slide">
                        <!--**box1**-->
                        <div class="service-box s-box5">
                            <!--icon-->
                            <i class="fa-solid fa-people-group"></i>
                            <!--title-->
                            <strong> Social Activities</strong>
                            <!--details-->
                            <p>We believe in promoting social interaction and well-being. Our caregivers can organize various activities, 
                                such as games, outings, and group discussions, to keep our clients engaged and active.</p>
                            <!--learn more-->
                            <a href="servicesPage.html#social-activities">Learn More</a>
                        </div>
                        </div><!--slide-end-->
                </div><!--wrapper-end-->
            </div><!--swiper-end-->

        </div>

        <!--btn-->
        
    
    </section><!--services-end-->


    <!--==Why-choose-us=======================-->
    <section id="why-choose-us">

        <!--**left*****************-->
        <div class="why-choose-us-left">
            <h3>Why Choose Us?</h3>
            <!--==container====-->
            <div class="why-choose-box-container">
                <!--**box**-->
                <div class="why-choose-box">
                    <!--icon-->
                    <i class="fa-solid fa-check"></i>
                    <!--text-->
                    <div class="why-choose-box-text">
                        <strong>Personalized Care</strong>
                        <p>Providing professional and compassionate in-home care for elderly and sick individuals.</p>
                    </div>
                </div>
                <!--**box**-->
                <div class="why-choose-box">
                    <!--icon-->
                    <i class="fa-solid fa-check"></i>
                    <!--text-->
                    <div class="why-choose-box-text">
                        <strong>Certified Caregivers</strong>
                        <p>Our team consists of highly trained and certified male and female caretakers.</p>
                    </div>
                </div>
                <!--**box**-->
                <div class="why-choose-box">
                    <!--icon-->
                    <i class="fa-solid fa-check"></i>
                    <!--text-->
                    <div class="why-choose-box-text">
                        <strong>Health Monitoring</strong>
                        <p>Regular check-ups and health updates to ensure ongoing well-being.</p>
                    </div>
                </div>
                <!--**box**-->
                <div class="why-choose-box">
                    <!--icon-->
                    <i class="fa-solid fa-check"></i>
                    <!--text-->
                    <div class="why-choose-box-text">
                        <strong>24/7 Support</strong>
                        <p>Always available to assist you with round-the-clock emergency care.</p>
                    </div>
                </div>
            </div>
            <!--==btn========-->
            <a href="booking.php" class="why-choose-us-btn">Make Booking</a>
        </div><!--left-end-->

        <!--**right*******************-->
        <div class="why-choose-us-right">
            <h3>Emergency?<br/>
                Contact Us
            </h3>
            <p>If you need urgent help, please get in touch:</p>
            <!--==container====-->
            <div class="w-right-contact-container">
                <!--**box**-->
                <div class="w-right-contact-box">
                    <i class="fa-solid fa-phone"></i>
                    <!--text-->
                    <div class="w-right-contact-box-text">
                        <span>Call Us Now</span>
                        <strong>+123 4567 890</strong>
                    </div>
                </div>
                <!--**box**-->
                <div class="w-right-contact-box">
                    <i class="fa-solid fa-envelope"></i>
                    <!--text-->
                    <div class="w-right-contact-box-text">
                        <span>Mail Us</span>
                        <strong>infolifelinecare@gmail.com</strong>
                    </div>
                </div>
            </div>
        </div><!--right-end-->

    </section><!--end-->



    <!--==Team===========================-->
    <section id="our-team">

        <!--**heading*****************-->
        <div class="our-team-heading">
            <h3>Meet Our Experts</h3>
            <p>Our team of expert caregivers and specialized nurses is dedicated to providing personalized care for individuals with complex health needs.</p>
        </div>

        <!--**team-container***********-->
        <div class="team-box-container">
            <!-- Swiper -->
            <div class="swiper mySwiperteam">
            <div class="swiper-wrapper">
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/N3.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Nurse Alex Smith</strong>
                            <span>Oncology Care Nurse</span>
                        </div>
                    </div>
                </div><!--end-slide-->
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/N2.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Nurse Angela Lee</strong>
                            <span>Stroke Recovery Nurse</span>
                        </div>
                    </div>
                </div><!--end-slide-->
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/N1.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Nurse Amanda Green</strong>
                            <span>Dementia Care Specialist</span>
                        </div>
                    </div>
                </div><!--end-slide-->
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/CG2.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Caregiver Maria Lopez </strong>
                            <span>Palliative Care Expert</span>
                        </div>
                    </div>
                </div><!--end-slide-->
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/CG1.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Caregiver Ashok Pandey</strong>
                            <span>Chronic Illness Support</span>
                        </div>
                    </div>
                </div><!--end-slide-->
                <!--**1***-->
                <div class="swiper-slide">
                    <!--**box**-->
                    <div class="team-box">
                        <!--img-->
                        <div class="team-img">
                            <img src="images/CG3.png" alt="">
                        </div>
                        <!--text-->
                        <div class="team-text">
                            <strong>Caregiver Lionel Dan</strong>
                            <span>Post-Surgery Care Expert</span>
                        </div>
                    </div>
                </div><!--end-slide-->
            </div><!--wrapper-end-->
            <div class="swiper-pagination"></div>
            </div><!--swiper-end-->

            
        
        </div>

    </section><!--team-end-->

     <!--==FAQ======================-->
     <section id="our-faq">

        <!--**heading********************-->
        <div class="faq-heading">
            <!--text-->
            <div class="faq-heading-text">
                <strong>Feedback</strong>
                <h2>Frequently Asked Questions</h2>
                
            </div>
            <!--btns-->
            <div class="faq-slide-btns">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

        <section>
        <!--**container*******************-->
        <div class="faq-box-container">

             <!-- Swiper -->
            <div class="swiper mySwiperfaq">
                <div class="swiper-wrapper">
                    <!--**1*******-->
                    <div class="swiper-slide">
                    <!--**box1**-->
                    <div class="faq-box f-box1">
                        
                        <!--title-->
                        <strong>What services does the Lifeline Care Center provide?</strong>
                        <!--details-->
                        <p>The Lifeline Care Center offers a wide range of caregiving services, including medical assistance, 
                            companionship, and day-to-day care for elderly or sick individuals. Families can connect with 
                            pre-vetted, highly skilled caregivers who cater to specific needs, such as administering medications, 
                            helping with mobility, assisting with personal hygiene, or providing emotional support. The platform 
                            ensures that all caregivers are trustworthy and reliable, offering peace of mind to families while 
                            addressing their loved ones' care requirements.</p>
                    </div>
                    </div><!--slide-end-->

                      <!--**1*******-->
                      <div class="swiper-slide">
                        <!--**box1**-->
                        <div class="faq-box f-box2">
                            
                            <!--title-->
                            <strong>How does the caregiver selection process work?</strong>
                            <!--details-->
                            <p>The platform uses a user-friendly interface that allows families to input their specific needs and 
                                preferences. Based on these details, the system matches them with pre-vetted caregivers who have 
                                the required skills and experience. Families can review caregiver profiles, which include qualifications,
                                 experience, and user reviews, to make informed decisions. The rigorous screening process ensures that 
                                 all caregivers are thoroughly verified, making safety and trust a top priority.</p>
                        </div>
                        </div><!--slide-end-->

                          <!--**1*******-->
                    <div class="swiper-slide">
                        <!--**box1**-->
                        <div class="faq-box f-box3">
                            
                            <!--title-->
                            <strong>How does Lifeline Care Center ensure the safety of its clients?</strong>
                            <!--details-->
                            <p>Safety is a cornerstone of the Lifeline Care Center. All caregivers undergo a rigorous
                                vetting process that includes background checks, verification of credentials, and interviews
                                to assess their expertise and reliability. Additionally, the platform encourages feedback
                                and ratings from families to maintain high service standards. This systematic approach ensures 
                                that clients receive care from professionals who are not only skilled but also trustworthy and 
                                compassionate.</p>
                        </div>
                        </div><!--slide-end-->

                          <!--**1*******-->
                    <div class="swiper-slide">
                        <!--**box1**-->
                        <div class="faq-box f-box4">
                            
                            <!--title-->
                            <strong>Is the platform accessible to people with limited technical expertise?</strong>
                            <!--details-->
                            <p>Yes, the Lifeline Care Center is designed to be accessible to users of all technical skill levels. 
                                The platform features a simple and intuitive interface that guides users step by step, from creating a 
                                profile to finding a caregiver. Clear instructions, helpful prompts, and responsive customer support 
                                ensure that families can easily navigate the system and access the care they need without any unnecessary 
                                complications.</p>
                        </div>
                        </div><!--slide-end-->
                    
                </div><!--wrapper-end-->
            </div><!--swiper-end-->

        </div>
    </section><!--FAQ-end-->

    



    <!--==Testimonials============================-->
    <section id="testimonials">

        <!--**heading****************-->
        <div class="testimonials-heading">
            <h3>Our Patients' FeedBack About Us</h3>
            <p>We’re proud to deliver compassionate, professional, and reliable care to our patients. Here’s what they have to say:</p>
        </div>


        <!--**testimonials-Content****-->
        <div class="testimonials-content">

            <!--**img************-->
            <div class="testimonials-img">
                <img src="images/backPic.jpg" alt="caregiving an elderly">
            </div>

            <!--**box-container**-->
            <div class="testimonials-box-container">

            <!-- Swiper -->
            <div class="swiper mySwipertesti">
            <div class="swiper-wrapper">
                <!--**1***********-->
                <div class="swiper-slide">
                <!--**box**-->
                <div class="testimonials-box">
                    <!--profile-->
                    <div class="t-profile">
                        <!--img-->
                        <div class="t-profile-img">
                            <img src="images/proPic1.jpg" alt="a woman">
                        </div>
                        <!--text-->
                        <div class="t-profile-text">
                            <strong>Anna Thompson</strong>
                            <span>From Colombo</span>
                            <div class="t-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                        </div>
                    </div>
                    <!--feedback-->
                    <p>"Lifeline Care Center truly changed my life. Their caregivers provided incredible support while I recovered from my surgery. I felt cared for, safe, and respected at all times."</p>
                </div><!--box-end-->
            </div><!--slide-end-->

            <!--**2***********-->
            <div class="swiper-slide">
                <!--**box**-->
                <div class="testimonials-box">
                    <!--profile-->
                    <div class="t-profile">
                        <!--img-->
                        <div class="t-profile-img">
                            <img src="images/proPic3.jpg" alt="">
                        </div>
                        <!--text-->
                        <div class="t-profile-text">
                            <strong>Daniel Silva</strong>
                            <span>From Galle</span>
                            <div class="t-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                                
                            </div>
                        </div>
                    </div>
                    <!--feedback-->
                    <p>"The dementia care my father received was beyond exceptional. The nurses were patient, understanding, and treated him with dignity. I’m forever grateful to Lifeline Care Center."</p>
                </div><!--box-end-->
            </div><!--slide-end-->

            <!--**3***********-->
            <div class="swiper-slide">
                <!--**box**-->
                <div class="testimonials-box">
                    <!--profile-->
                    <div class="t-profile">
                        <!--img-->
                        <div class="t-profile-img">
                            <img src="images/proPic2.jpg" alt="">
                        </div>
                        <!--text-->
                        <div class="t-profile-text">
                            <strong>Sera Anthony</strong>
                            <span>From Kandy</span>
                            <div class="t-rating">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <!--feedback-->
                    <p>"After my cancer diagnosis, Lifeline Care Center’s specialized nurses gave me the care and emotional support I needed. Their kindness and professionalism made my journey so much easier."</p>
                </div><!--box-end-->
            </div><!--slide-end-->
            
            </div><!--wrapper-end-->
            <div class="swiper-pagination"></div>
            </div><!--swiper-end-->

            </div><!--container-end-->
        
        </div><!--content-end-->

    </section><!--testimonials-end-->



    <!--==Subscribe===========================-->
    <section id="subscribe">
        <h3>Subscribe For New Updates From Lifeline Care Center</h3>
        <!--subcribe-box-->
        <form class="subscribe-box" id="subscribeForm">
            <input type="email" id="subscribeEmail" placeholder="Example@gmail.com" required>
            <button type="submit">Subscribe</button>
            <div class="message" id="subscribeMessage"></div>
        </form>
    </section><!--end-->


    <footer>
        <div class="footer-container">
            <!--**comoany-box**-->
            <div class="footer-company-box">
                <!--logo-->
                <a href="#" class="logo-name"><span>Lifeline</span>Care Center</a>
                <!--details-->
                <p>Lifeline Care Center – Compassionate care and support for recovery, chronic conditions, and daily well-being.</p>
                <!--social-box-->
                <div class="footer-social">
                    <a href="https://www.facebook.com/profile.php?id=61570740961821&mibextid=ZbWKwL" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    
                </div>
            </div>
            <!--**link-box***-->
            <div class="footer-link-box">
                <strong>Main Link's</strong>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#about-us">About Us</a></li>
                    <li><a href="#why-choose-us">Contact Us</a></li>
                    <li><a href="#our-services">Our Services</a></li>
                    <li><a href="#our-team">Portfolio</a></li>
                </ul>
            </div>
            
            <!--**link-box***-->
            <div class="footer-link-box">
                <strong>External Link's</strong>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Disclaimer</a></li>
                    <li><a href="#">Term's and Condition's</a></li>
                    <li><a href="review.php">Your Reviews</a></li>
                </ul>
            </div>
            
   
        </div>
        

        <!--**bottom**********************-->
        <div class="footer-bottom">
            <span class="footer-owner">Made By Lifeline Care (Group 2)</span>
            <span class="copyright">Copyright © 2024 - Lifeline Care Center. All Rights Reserved.</span>
        </div>
    </footer>




    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    /*-services--*/
    var swiper = new Swiper(".mySwiperservices", {
        slidesPerView: 3,
        spaceBetween: 10,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          700: {
            slidesPerView: 3,
            spaceBetween: 40,
          },
          1024: {
            slidesPerView: 3,
            spaceBetween: 60,
          },
        },
      });

       /*-FAQ--*/
    var swiper = new Swiper(".mySwiperfaq", {
        slidesPerView: 1,
        spaceBetween: 10,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        breakpoints: {
          700: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
          1024: {
            slidesPerView: 1,
            spaceBetween: 10,
          },
        },
      });
    /*--team--*/
    var swiper = new Swiper(".mySwiperteam", {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
        breakpoints: {
          560: {
            slidesPerView: 2,
            spaceBetween: 20,
          },
          950: {
            slidesPerView: 3,
            spaceBetween: 40,
          },
          1250: {
            slidesPerView: 4,
            spaceBetween: 40,
          },
        },
      });
    /*--testimonials--*/
      var swiper = new Swiper(".mySwipertesti", {
        pagination: {
          el: ".swiper-pagination",
          clickable: true,
        },
      });

      document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.querySelector('.caregiver-search');
    const caregiverInput = document.getElementById('caregiver-search');
    const locationInput = document.getElementById('location-search');
    
    // Create results container if it doesn't exist
    let resultsContainer = document.querySelector('.search-results');
    if (!resultsContainer) {
        resultsContainer = document.createElement('div');
        resultsContainer.className = 'search-results';
        searchForm.parentNode.appendChild(resultsContainer);
    }

    // Add CSS styles
    const style = document.createElement('style');
    style.textContent = `
        .search-results {
            margin-top: 20px;
            padding: 20px;
        }
        .caregiver-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .caregiver-card h4 {
            margin: 0 0 10px 0;
            color: #444dc9;
        }
        .caregiver-info {
            font-size: 14px;
            margin: 5px 0;
            color: #666;
        }
        .available { color: #28a745; }
        .unavailable { color: #dc3545; }
    `;
    document.head.appendChild(style);

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const searchTerm = caregiverInput.value.trim();
        const location = locationInput.value.trim();

        // Show loading state
        resultsContainer.innerHTML = '<div style="text-align: center;">Searching...</div>';

        // Make the search request
        fetch(`search_caregivers.php?search=${encodeURIComponent(searchTerm)}&location=${encodeURIComponent(location)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    displayResults(data.data);
                } else {
                    throw new Error(data.message || 'Error searching for caregivers');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultsContainer.innerHTML = `
                    <div style="text-align: center; color: #dc3545;">
                        Error searching for caregivers. Please try again.
                    </div>
                `;
            });
    });

    // Display results function
    function displayResults(caregivers) {
        resultsContainer.innerHTML = '';

        if (caregivers.length === 0) {
            resultsContainer.innerHTML = `
                <div style="text-align: center;">
                    No caregivers found matching your search criteria.
                </div>
            `;
            return;
        }

        caregivers.forEach(caregiver => {
            const card = document.createElement('div');
            card.className = 'caregiver-card';
            card.innerHTML = `
                <h4>${caregiver.full_name}</h4>
                <div class="caregiver-info">
                    <div><strong>Position:</strong> ${caregiver.position}</div>
                    <div><strong>Experience:</strong> ${caregiver.experience}</div>
                    <div><strong>Location:</strong> ${caregiver.location}</div>
                    <div class="${caregiver.availability.toLowerCase() === 'available' ? 'available' : 'unavailable'}">
                        <strong>Status:</strong> ${caregiver.availability}
                    </div>
                </div>
            `;
            resultsContainer.appendChild(card);
        });
    }
});
    </script>

    
    <script>
        // ------------- js for subscribe form --------


      // Frontend JavaScript
      document.addEventListener('DOMContentLoaded', function() {
    const subscribeForm = document.querySelector('.subscribe-box');
    const emailInput = subscribeForm.querySelector('input[type="email"]');
    
    subscribeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = emailInput.value.trim();
        
        // Basic email validation
        if (!isValidEmail(email)) {
            showMessage('Please enter a valid email address', 'error');
            return;
        }
        
        // Disable the submit button while processing
        const submitButton = subscribeForm.querySelector('button');
        submitButton.disabled = true;
        
        // Send subscription request
        fetch('subscribe.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage('Thank you for subscribing!', 'success');
                emailInput.value = ''; // Clear input
            } else {
                showMessage(data.message || 'Subscription failed. Please try again.', 'error');
            }
        })
        .catch(error => {
            showMessage('An error occurred. Please try again later.', 'error');
            console.error('Error:', error);
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });
    
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function showMessage(message, type) {
        // Remove any existing message
        const existingMessage = document.querySelector('.subscription-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Create new message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `subscription-message ${type}`;
        messageDiv.textContent = message;
        
        // Insert message after the form
        subscribeForm.parentNode.insertBefore(messageDiv, subscribeForm.nextSibling);
        
        // Remove message after 5 seconds
        setTimeout(() => {
            messageDiv.remove();
        }, 5000);
    }
});

// Toggle chatbot visibility
const chatbotToggle = document.getElementById('chatbot-toggle');
const chatbotContainer = document.querySelector('.chatbot-container');
const closeChat = document.getElementById('close-chat');
const chatSuggestions = document.getElementById('chat-suggestions');
const chatBody = document.getElementById('chat-body');
const chatInput = document.getElementById('chat-input');
const sendBtn = document.getElementById('send-btn');

// Show and hide chatbot
chatbotToggle.addEventListener('click', () => {
  chatbotContainer.style.display = 'flex';
  chatbotToggle.style.display = 'none';
});

closeChat.addEventListener('click', () => {
  chatbotContainer.style.display = 'none';
  chatbotToggle.style.display = 'block';
});

// Function to add message to chat
function addMessage(text, sender) {
  const message = document.createElement('div');
  message.classList.add('message', sender);
  message.textContent = text;
  chatBody.appendChild(message);
  chatBody.scrollTop = chatBody.scrollHeight; // Auto-scroll
}

// Chatbot responses based on keyword matching
function botReply(userMessage) {
  const responses = {
    "what are your services?": "We offer home care services such as daily assistance, medication management, post-surgical care, and more.",
    "how much do your services cost?": "The cost varies depending on the service. You can check prices on our website or contact support for details.",
    "booking caretakers": "You can book a caretaker by signing into your account, browsing caretakers, and scheduling an appointment.",
    "can I choose my caretaker?": "Yes, you can browse caretaker profiles and select the one that best suits your needs.",
    "cancel booking": "Go to 'My Bookings' in your account to cancel a booking. Cancellation policies may apply.",
    "contact": "You can contact our support team via email at infolifelinecarecenter@lifelinecare.com or call +94 123 4567 890.",
    "where are you located?": "We operate in multiple cities. Enter your location on our website to check caretaker availability.",
    "are your caretakers certified?": "Yes, all our caretakers are vetted, trained, and certified to provide professional care services.",
    "bye": "Goodbye! Have a great day!",
    "hi" : "Hi! How can I help you today?",
    "hello": "Hello there! How can I assist you?",
    "thanks": "You're welcome! Let me know if you need anything else."
  };

  let foundResponse = "I'm sorry, I didn't understand that. Can you rephrase?";
  
  // Match user input with keywords in the responses
  Object.keys(responses).forEach((key) => {
    if (userMessage.toLowerCase().includes(key)) {
      foundResponse = responses[key];
    }
  });

  addMessage(foundResponse, 'bot');
}

// Handle sending message (typed input)
sendBtn.addEventListener('click', () => {
  const userMessage = chatInput.value.trim();
  if (userMessage) {
    addMessage(userMessage, 'user'); // Add user message to chat
    chatInput.value = ''; // Clear input field
    setTimeout(() => botReply(userMessage), 500); // Bot response
  }
});

// Suggested questions
const suggestions = [
  "what are your services?",
  "how much do your services cost?",
  "booking caretakers",
  "can I choose my caretaker?",
  "cancel booking",
  "where are you located?",
  "are your caretakers certified?"
];

// Dynamically add suggestion buttons
function addSuggestionButtons() {
  chatSuggestions.innerHTML = ""; // Clear previous suggestions
  suggestions.forEach((suggestion) => {
    const button = document.createElement('button');
    button.textContent = suggestion;
    button.className = 'suggestion-btn'; // Add styling class
    button.addEventListener('click', () => {
      addMessage(suggestion, 'user'); // Show clicked question in chat
      setTimeout(() => botReply(suggestion.toLowerCase()), 500); // Bot replies
    });
    chatSuggestions.appendChild(button);
  });
}

// Load suggestions on chatbot open
addSuggestionButtons();

    </script>


</body>
</html>