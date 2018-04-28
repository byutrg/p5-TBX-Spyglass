use strict;
use warnings;

use XML::Twig;

sub main
{
    #THIS LINE DOES NOT WORK ON THE SERVER    
    #die "File does not have a '.tbx' extension.\n" if $_[0] !~ /\.tbx$/;
    my $isTBX = 0;
    
    my $twig = XML::Twig->new(
        twig_handlers =>
        {
            tbx => sub {
                    printf "File appears to be a 2018 TBX file with dialect: '%s'", $_->att('type');
                    $isTBX = 1;
                },
            martif => sub {
                printf "File appears to be a 2008 TBX file.";
                $isTBX = 1;
            }
        }
    );
    
    die "File does not appear to be well-formed XML.\n" unless $twig->safe_parsefile($_[0]);
    print "File appears to be well-formed XML, but does not appear to be TBX." if $isTBX == 0;
}

die "Missing target file.\n" if (@ARGV < 2);
main($ARGV[0]);