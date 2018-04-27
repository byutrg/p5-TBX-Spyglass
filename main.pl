use strict;
use warnings;

use XML::Twig;

sub main
{
    print "File does not have a '.tbx' extension." if $_[0] !~ /\.tbx$/;
    
    my $twig = XML::Twig->new(
        twig_handlers =>
        {
            tbx => sub {
                    printf "File appears to be a 2018 TBX file with dialect: '%s'", $_->att('type');
                },
            martif => sub {
                printf "File appears to be a 2008 TBX file."
            }
        }
    );
    
    print "File does not appear to be valid XML." unless $twig->safe_parsefile($_[0]);
}

die "Missing target file." if (@ARGV != 1);
main($ARGV[0]);