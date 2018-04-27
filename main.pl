use strict;
use warnings;

use XML::Twig;

my $return_instructions = "\n\nGo back in browser to return to TBX Spyglass utility.\n";

sub main
{
    #THIS LINE DOES NOT WORK ON THE SERVER    
    #die "File does not have a '.tbx' extension.\n" if $_[0] !~ /\.tbx$/;
    
    my $twig = XML::Twig->new(
        twig_handlers =>
        {
            tbx => sub {
                    printf "File appears to be a 2018 TBX file with dialect: '%s'$return_instructions", $_->att('type');
                },
            martif => sub {
                printf "File appears to be a 2008 TBX file.$return_instructions"
            }
        }
    );
    
    die "File does not appear to be valid XML.\n$return_instructions" unless $twig->safe_parsefile($_[0]);
}

die "Missing target file.\n$return_instructions" if (@ARGV < 2);
main($ARGV[0]);