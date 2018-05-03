use strict;
use warnings;

use XML::Twig;

sub main
{
    #THIS LINE DOES NOT WORK ON THE SERVER    
    #die "File does not have a '.tbx' extension.\n" if $_[0] !~ /\.tbx$/;
    
    ## ordered by severity
    my %messages = (
        malformed_xml => "File does not appear to be well-formed XML.",
        malformed_tbx => "File appears to be well-formed XML, but does not appear to be TBX.",
        invalid_tbx => "File claims to be TBX, but does not appear valid.",
        2008 => "File appears to be a 2008 TBX file.",
        bad_2018 => "File appears to be a 2018, but has no valid dialect.",
        2018 => "File appears to be a 2018 TBX file with dialect:",
    );
    
    
    my $twig = XML::Twig->new(
        twig_handlers =>
        {
            ## TBX-Min originally had a mandatory upper-case <TBX> root element, but this is not legal in any other case
            TBX => sub {
                if ($_->att('dialect') eq "TBX-Min") { print $messages{'2008'}."\n" }
                else { print $messages{'invalid_tbx'}."\n" }
            },
            ## 2008 TBX has <martif> lower-case.
            martif => sub {
                print $messages{'2008'}."\n";
            },
            ## 2018 TBX files have a mandator lower-case <tbx> root element
            tbx => sub {
                if ($_->att('type') =~ /^TBX-.+?/)
                {
                    printf $messages{'2018'}." '%s'\n", $_->att('type');    
                }
                else {
                    print $messages{'bad_2018'}."\n";
                }
            },
            'MARTIF' => sub {
                print $messages{'invalid_tbx'}."\n";
            }
        }
    );
    
    die $messages{'malformed_xml'}."\n" unless $twig->safe_parsefile($_[0]);
}

die "Missing target file.\n" if (@ARGV < 1);
main($ARGV[0]);