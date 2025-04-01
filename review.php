<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8" />
    <title>Review & Rating</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5 mb-5">Review & Rating</h1>
        <div class="card">
            <div class="card-header">How satisfied are you with our service?</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <h1 class="text-warning mt-4 mb-4">
                            <b><span id="average_rating">0.0</span> / 5</b>
                        </h1>
                        <div class="mb-3">
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                        </div>
                        <h3><span id="total_review">0</span> Reviews</h3>
                    </div>
                    <div class="col-sm-4">
                        <p>
                            <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span id="total_five_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                            </div>
                        </p>
                        <p>
                            <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span id="total_four_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                            </div>
                        </p>
                        <p>
                            <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span id="total_three_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                            </div>
                        </p>
                        <p>
                            <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span id="total_two_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                            </div>
                        </p>
                        <p>
                            <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span id="total_one_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                            </div>
                        </p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h3 class="mt-4 mb-3">Write Review Here</h3>
                        <button type="button" name="add_review" id="add_review" class="btn btn-primary">Review</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5" id="review_content"></div>
    </div>

    <div id="review_modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Submit Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 class="text-center mt-2 mb-4">
                        <i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
                        <i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
                        <i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
                        <i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
                        <i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
                    </h4>
                    <div class="form-group">
                        <input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Your Name" />
                    </div>
                    <div class="form-group">
                        <input type="text" name="user_from" id="user_from" class="form-control" placeholder="Enter Your Location" />
                    </div>
                    <div class="form-group">
                        <textarea name="user_review" id="user_review" class="form-control" placeholder="Type Review Here"></textarea>
                    </div>
                    <div class="form-group text-center mt-4">
                        <button type="button" class="btn btn-primary" id="save_review">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
      $(document).ready(function() {
            // Open review modal on button click
            $('#add_review').click(function() {
                $('#review_modal').modal('show');
            });

            // Handle review submission
            $('#save_review').click(function() {
                var user_name = $('#user_name').val();
                var user_from = $('#user_from').val();
                var user_review = $('#user_review').val();
                var user_rating = $('.submit_star.text-warning').last().data('rating');

                if (user_name == '' || user_from == '' || user_review == '' || !user_rating) {
                    alert('Please fill all fields and select a rating.');
                    return;
                }

                
            });

            // Change star rating on hover and click
            $('.submit_star').hover(function() {
                var rating = $(this).data('rating');
                resetStars();
                highlightStars(rating);
            });

            $('.submit_star').click(function() {
                var rating = $(this).data('rating');
                resetStars();
                highlightStars(rating);
                $(this).addClass('text-warning');
            });

            function resetStars() {
                $('.submit_star').removeClass('text-warning');
            }

            function highlightStars(rating) {
                for (var i = 1; i <= rating; i++) {
                    $('#submit_star_' + i).addClass('text-warning');
                }
            }
        });

        $(document).ready(function() {
            // Retrieve stored reviews and star counts from localStorage
            var reviews = JSON.parse(localStorage.getItem('reviews')) || [];
            var starCounts = JSON.parse(localStorage.getItem('starCounts')) || [0, 0, 0, 0, 0];

            var selectedRating = 0;

            // Function to display all reviews
            function displayReviews() {
                var output = '';
                reviews.forEach(function(review) {
                    output += '<div class="review-item mb-3">';
                    output += '<p><strong>' + review.name + '</strong> from ' + review.location + ' rated this ' + review.rating + ' stars.</p>';
                    output += '<p>' + review.review + '</p>';
                    output += '</div>';
                });
                $('#review_content').html(output);
            }

            // Function to update the rating statistics
            function updateRatingBar() {
                var totalReviews = reviews.length;
                var totalFiveStars = starCounts[4];
                var totalFourStars = starCounts[3];
                var totalThreeStars = starCounts[2];
                var totalTwoStars = starCounts[1];
                var totalOneStar = starCounts[0];

                // Update total number of reviews
                $('#total_review').text(totalReviews);

                // Update total for each star rating
                $('#total_five_star_review').text(totalFiveStars);
                $('#total_four_star_review').text(totalFourStars);
                $('#total_three_star_review').text(totalThreeStars);
                $('#total_two_star_review').text(totalTwoStars);
                $('#total_one_star_review').text(totalOneStar);

                // Update progress bars
                $('#five_star_progress').css('width', (totalFiveStars / totalReviews) * 100 + '%');
                $('#four_star_progress').css('width', (totalFourStars / totalReviews) * 100 + '%');
                $('#three_star_progress').css('width', (totalThreeStars / totalReviews) * 100 + '%');
                $('#two_star_progress').css('width', (totalTwoStars / totalReviews) * 100 + '%');
                $('#one_star_progress').css('width', (totalOneStar / totalReviews) * 100 + '%');
            }

            // Function to update the average rating
            function updateAverageRating() {
                var totalStars = 0;
                reviews.forEach(function(review) {
                    totalStars += review.rating;
                });
                var averageRating = (totalStars / reviews.length).toFixed(1);
                $('#average_rating').text(averageRating);
                $('#total_review').text(reviews.length);
            }

            // Load initial data
            displayReviews();
            updateRatingBar();
            updateAverageRating();

            // Star rating selection
            $('.submit_star').click(function() {
                selectedRating = $(this).data('rating');
                $('.submit_star').removeClass('text-warning');
                for (var i = 0; i < selectedRating; i++) {
                    $('.submit_star').eq(i).addClass('text-warning');
                }
            });

            // Save review and update localStorage
            $('#save_review').click(function() {
                var user_name = $('#user_name').val();
                var user_from = $('#user_from').val();
                var user_review = $('#user_review').val();

                if (user_name == '' || user_from == '' || user_review == '' || selectedRating === 0) {
                    alert('Please fill all fields and provide a valid rating.');
                    return;
                }

                // Add the new review to the reviews array
                reviews.push({
                    name: user_name,
                    location: user_from,
                    review: user_review,
                    rating: selectedRating
                });

                // Update the count of each rating (1 to 5 stars)
                starCounts[selectedRating - 1]++;

                // Update the review display and rating statistics
                displayReviews();
                updateRatingBar();
                updateAverageRating();

                // Save the updated reviews and star counts to localStorage
                localStorage.setItem('reviews', JSON.stringify(reviews));
                localStorage.setItem('starCounts', JSON.stringify(starCounts));

                // Close the modal
                $('#review_modal').modal('hide');
                $('#review_modal').find('input, textarea').val('');
                $('.submit_star').removeClass('text-warning');
                selectedRating = 0;
            });
        });



    </script>
</body>
</html>
