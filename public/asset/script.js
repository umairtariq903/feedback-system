$(document).ready(function () {
    var selectedMention = "";

    // Attach the input event listener to all comment inputs
    $('.comment_request').on('input', function () {
        var postId = $(this).data('id');
        var commentInput = $('#comment-' + postId);
        var selectedMentionInput = $('#selected-mention-' + postId);

        var mentionList = $('#mention-list-' + postId);

        var commentText = commentInput.val();

        if (commentText.includes('@')) {
            $.ajax({
                url: "{{ route('getMentions') }}",
                method: 'GET',
                data: {term: commentText},
                dataType: 'json',
                success: function (users) {
                    mentionList.empty();
                    users.forEach(function (user) {
                        mentionList.append('<div class="assign_user my-1" data-id="' + user.id + '" data-mention="' + user.name + '">' + user.name + '</div>');
                    });
                    mentionList.show();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        } else {
            mentionList.hide();
        }
    });

    // Handle mention selection
    $(document).on('click', '.assign_user', function () {
        var postId = $(this).closest('.comment-section').find('.comment_request').data('id');

        var commentInput = $('#comment-' + postId);
        var selectedMentionInput = $('#selected-mention-' + postId);
        var selectedMentionId = $('#selected-mentions_id-' + postId);
        var mentionList = $('#mention-list-' + postId);

        // Clear the input and hide the mention list
        commentInput.val('');
        mentionList.hide();

        // Append the selected mention to the cleared input
        selectedMention = $(this).data('mention');
        let selectedId = $(this).data('id');


        // Update the selected mention input
        selectedMentionId.val(selectedId);
        selectedMentionInput.val(selectedMention);
    });
});

